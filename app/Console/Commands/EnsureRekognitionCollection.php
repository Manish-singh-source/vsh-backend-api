<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RekognitionService;

class EnsureRekognitionCollection extends Command
{
    protected $signature = 'rekognition:ensure-collection';
    protected $description = 'Ensure the AWS Rekognition collection exists';

    protected RekognitionService $rekognition;

    public function __construct(RekognitionService $rekognition)
    {
        parent::__construct();
        $this->rekognition = $rekognition;
    }

    public function handle()
    {
        $this->info('Ensuring Rekognition collection exists...');

        try {
            $result = $this->rekognition->ensureCollection();
            $this->info("Collection ensured: " . ($result['CollectionArn'] ?? $result['CollectionARN'] ?? 'unknown'));
            return 0;
        } catch (\Throwable $e) {
            $this->error('Failed to ensure collection: ' . $e->getMessage());
            return 1;
        }
    }
}
