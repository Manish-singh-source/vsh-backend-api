<?php

namespace App\Jobs;

use App\Models\Visitor;
use App\Services\RekognitionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class IndexVisitorFace implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $visitorId;
    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(Visitor $visitor)
    {
        $this->visitorId = $visitor->id;
    }

    /**
     * Execute the job.
     */
    public function handle(RekognitionService $rekognitionService): void
    {
        $visitor = Visitor::find($this->visitorId);
        if (! $visitor || ! $visitor->image_path) {
            return;
        }

        // resolve local file path
        if (Storage::exists($visitor->image_path)) {
            $localPath = Storage::path($visitor->image_path);
        } elseif (file_exists($visitor->image_path)) {
            $localPath = $visitor->image_path;
        } else {
            // cannot find image
            logger()->warning("IndexVisitorFace: image not found for visitor {$this->visitorId} at {$visitor->image_path}");
            return;
        }

        try {
            $bytes = file_get_contents($localPath);

            $faceId = $rekognitionService->detectAndIndexFromBytes($bytes, 'visitor_' . $visitor->id);

            if ($faceId) {
                $visitor->face_id = $faceId;
                $visitor->save();
            } else {
                logger()->info("IndexVisitorFace: no face indexed for visitor {$this->visitorId}");
            }
        } catch (\Exception $e) {
            logger()->error('IndexVisitorFace error for visitor ' . $this->visitorId . ': ' . $e->getMessage());
        }
    }

    /**
     * Crop bytes by bounding box (BoundingBox array from Rekognition) and return JPEG bytes
     */
    protected function cropBytesByBoundingBox(string $bytes, array $box): ?string
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
}
