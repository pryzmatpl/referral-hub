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
     * @param array $skills Job keywords array
     * @return array Classification results with category and confidence scores
     * @throws \Exception If classification fails
     */
    public function classifyJob(array $skills): array
    {
        try {
            // Prepare the skills string (space-separated if that's what your script expects)
            $skillsStr = implode(' ', $skills);

            // Use the Python interpreter directly from the virtual environment
            $pythonExecutable = $this->scriptPath . "/.venv/bin/python";

            // Build the command as an array: no need for "activate" or shell operators
            $command = [
                $pythonExecutable,
                $this->scriptPath . "/run.py",
                $skillsStr
            ];

            // Create and run the process
            $process = new Process($command);
            $this->logger->debug("Running command: " . $process->getCommandLine());
            $process->run();

            // Check if the process ran successfully
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Get and log the output
            $result = $process->getOutput();
            $this->logger->debug("Output: " . $result);

            // Use a regular expression to extract the JSON array (assuming it starts with '[' and ends with ']')
            if (preg_match('/(\[.*\])\s*$/s', $result, $matches)) {
                $json = $matches[1];
                $decoded = json_decode($json, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("Invalid JSON: " . json_last_error_msg());
                }
            } else {
                throw new \Exception("No valid JSON found in output.");
            }

            $this->logger->info('Job classified successfully', [
                'category' => $decoded['category'] ?? 'unknown'
            ]);

            return $decoded;
        } catch (\Exception $e) {
            // Handle the exception as needed
            $this->logger->error('Error running classification script: ' . $e->getMessage());
            throw $e;
        }

    }

}