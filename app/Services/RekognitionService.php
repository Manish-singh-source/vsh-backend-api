<?php

namespace App\Services;

use Aws\Rekognition\RekognitionClient;
use Aws\Rekognition\RekognitionException;

class RekognitionService
{
    protected RekognitionClient $client;
    protected string $collectionId;

    public function __construct()
    {
        $rek = config('services.rekognition');

        $this->collectionId = $rek['collection'] ?? env('AWS_REKOGNITION_COLLECTION');

        $this->client = new RekognitionClient([
            'region' => $rek['region'] ?? env('AWS_DEFAULT_REGION', 'us-east-1'),
            'version' => $rek['version'] ?? 'latest',
            'credentials' => [
                'key' => $rek['key'] ?? env('AWS_ACCESS_KEY_ID'),
                'secret' => $rek['secret'] ?? env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    /** Ensure the collection exists; return AWS SDK Result (describe or create) */
    public function ensureCollection(): \Aws\Result
    {
        if (! $this->collectionId) {
            throw new \RuntimeException('Rekognition collection id not configured (services.rekognition.collection or AWS_REKOGNITION_COLLECTION).');
        }

        try {
            $result = $this->client->describeCollection(['CollectionId' => $this->collectionId]);
            return $result;
        } catch (\Aws\Exception\AwsException $e) {
            // create if not found (AwsException contains getAwsErrorCode)
            $code = method_exists($e, 'getAwsErrorCode') ? ($e->getAwsErrorCode() ?? '') : '';
            if (str_contains($code, 'ResourceNotFound') || str_contains($e->getMessage(), 'ResourceNotFound')) {
                $result = $this->client->createCollection(['CollectionId' => $this->collectionId]);
                return $result;
            }

            throw $e;
        } catch (\Throwable $e) {
            // some HTTP errors or Guzzle exceptions may not be AwsException but still indicate ResourceNotFound
            if (str_contains($e->getMessage(), 'ResourceNotFound')) {
                $result = $this->client->createCollection(['CollectionId' => $this->collectionId]);
                return $result;
            }

            throw $e;
        }
    }

    /** Detect faces from raw image bytes; returns array of FaceDetails */
    public function detectFacesFromBytes(string $bytes, array $attributes = ['ALL']): array
    {
        $result = $this->client->detectFaces([
            'Image' => ['Bytes' => $bytes],
            'Attributes' => $attributes,
        ]);

        return $result['FaceDetails'] ?? [];
    }

    /** Index faces from raw image bytes. Returns first FaceId or null */
    public function indexFaceFromBytes(string $bytes, ?string $externalId = null): ?string
    {
        $this->ensureCollection();

        $params = [
            'CollectionId' => $this->collectionId,
            'Image' => ['Bytes' => $bytes],
            'DetectionAttributes' => [],
        ];

        if ($externalId) {
            $params['ExternalImageId'] = $externalId;
        }

        $result = $this->client->indexFaces($params);

        if (! empty($result['FaceRecords'])) {
            return $result['FaceRecords'][0]['Face']['FaceId'] ?? null;
        }

        return null;
    }

    /** Index a face by file path */
    public function indexFaceFromFile(string $path, ?string $externalId = null): ?string
    {
        if (! file_exists($path)) {
            throw new \InvalidArgumentException('Image file not found: ' . $path);
        }
        $bytes = file_get_contents($path);
        return $this->indexFaceFromBytes($bytes, $externalId);
    }

    /** Crop image bytes using Rekognition BoundingBox and return JPEG bytes */
    public function cropBytesByBoundingBox(string $bytes, array $box): ?string
    {
        $src = @imagecreatefromstring($bytes);
        if (! $src) {
            return null;
        }

        $origW = imagesx($src);
        $origH = imagesy($src);

        $x = max(0, (int) round($box['Left'] * $origW));
        $y = max(0, (int) round($box['Top'] * $origH));
        $w = max(1, (int) round($box['Width'] * $origW));
        $h = max(1, (int) round($box['Height'] * $origH));

        // ensure crop bounds within image
        if ($x + $w > $origW) {
            $w = $origW - $x;
        }
        if ($y + $h > $origH) {
            $h = $origH - $y;
        }

        $dst = imagecreatetruecolor($w, $h);
        imagecopyresampled($dst, $src, 0, 0, $x, $y, $w, $h, $w, $h);

        ob_start();
        imagejpeg($dst, null, 90);
        $data = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return $data ?: null;
    }

    /** Detect and index first face in bytes; returns FaceId or null */
    public function detectAndIndexFromBytes(string $bytes, ?string $externalId = null): ?string
    {
        $faces = $this->detectFacesFromBytes($bytes);
        if (empty($faces)) {
            return null;
        }

        $box = $faces[0]['BoundingBox'];
        $crop = $this->cropBytesByBoundingBox($bytes, $box);
        if (! $crop) {
            return null;
        }

        return $this->indexFaceFromBytes($crop, $externalId);
    }

    /** Search for matches by image bytes; returns array of matches */
    public function searchByImageBytes(string $bytes, float $threshold = 85.0, int $maxFaces = 5): array
    {
        $this->ensureCollection();

        $result = $this->client->searchFacesByImage([
            'CollectionId' => $this->collectionId,
            'Image' => ['Bytes' => $bytes],
            'FaceMatchThreshold' => $threshold,
            'MaxFaces' => $maxFaces,
        ]);

        return $result['FaceMatches'] ?? [];
    }
}
