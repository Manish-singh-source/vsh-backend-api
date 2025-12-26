<?php
// app/Services/QrCodeService.php - Endroid version

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    public static function generateQrForUser(array $payload, string $fileName): string
    {
        $directory = public_path('qrcodes');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory . '/' . $fileName;

        $result = Builder::create()
            ->data(json_encode($payload))
            ->size(300)
            ->build();

        $writer = new PngWriter();
        $writer->writeResult($result, $path);

        return 'qrcodes/' . $fileName;
    }
}
