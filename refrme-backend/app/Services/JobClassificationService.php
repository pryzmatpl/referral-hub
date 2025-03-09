<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 09.03.2025, 12:37
 * JobClassificationService.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */
namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class JobClassificationService
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var string
     */
    private string $pythonPath;

    /**
     * @var string
     */
    private string $scriptPath;


    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     * @param string $pythonPath Path to Python interpreter (default: 'python')
     * @param string $scriptPath Path to the Python script directory
     */
    public function __construct(
        LoggerInterface $logger,
        string $pythonPath = 'python',
        string $scriptPath = __DIR__ . '../models/match'
    ) {
        $this->logger = $logger;
        $this->pythonPath = $pythonPath;
        $this->scriptPath = $scriptPath;
    }

    /**
     * Classify a job description
     *
     * @param array $description Job keywords array
     * @return array Classification results with category and confidence scores
     * @throws \Exception If classification fails
     */
    public function classifyJob(array $description): array
    {
        try {
            // Run Python classification script

            $process = new Process([
                $this->pythonPath,
                $this->scriptPath . '/run.py',
                implode(',', $description)
            ]);

            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Parse classification results
            $result = json_decode($process->getOutput(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response from classifier: ' . json_last_error_msg());
            }

            $this->logger->info('Job classified successfully', [
                'category' => $result['category'] ?? 'unknown'
            ]);

            return $result;
        } catch (\Exception $e) {
            $this->logger->error('Job classification failed: ' . $e->getMessage());
            throw $e;
        }
    }

}