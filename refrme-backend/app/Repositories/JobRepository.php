<?php
/**
 * This code is part of referalhub
 * All rights reserved
 * 2024 08 08 18 32
 */
namespace App\Repositories;

use App\Models\Job;
use App\Services\JobService;
use Codeception\Extension\Logger;
use Illuminate\Database\Eloquent\Collection;
use Psr\Log\LoggerInterface;

class JobRepository {
    private JobService $jobService;
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger, JobService $jobService)
    {
        $this->jobService=$jobService;
        $this->logger=$logger;
    }

    public function all(): Collection|array
    {
        return Job::all();
    }

    public function search(array $params): Collection|array
    {
        return Job::query()->get($params);
    }

    public function findById(int $id): ?Job {
        return Job::with('company')->find($id);
    }

    public function save(array $params): Job
    {
        try {
            return $this->jobService->createJob($params);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return Job::InvalidJob();
    }
}
