<?php

namespace App\Services;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Create a new class instance.
     */
    public function __construct(array $payload, string $fileName)
    {
        //
        $directory = public_path('qrcodes');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory . '/' . $fileName;

        $content = json_encode($payload);

        QrCode::format('png')
            ->size(300)
            ->generate($content, $path);

        // store relative path to serve via storage or direct public
        return 'qrcodes/' . $fileName;
    }
}
