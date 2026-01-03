<?php
// app/Services/QrCodeService.php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    public static function generateQrForUser(array $payload, string $fileName): string
    {
        $directory = public_path('qrcodes');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory . '/' . $fileName;

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data(json_encode($payload))
            ->size(300)
            ->margin(10)
            ->build();

        // ✅ v5 way to save file
        $result->saveToFile($path);

        return $path;
    }
}
