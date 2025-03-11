<?php
/**
 * This code is part of referalhub
 * 2024 08 08 18 34
 */
namespace App\Services;

use App\Models\JobWeight;
use App\Repositories\JobRepository;
use App\Models\Job;
use App\Models\User;
use \Exception;
use Illuminate\Database\Eloquent\Collection;

class JobService {
    private JobRepository $jobRepository;
    private JobClassificationService $jobClassificationService;

    public function __construct(
        JobRepository $jobRepository,
        JobClassificationService $jobClassificationService
    ) {
        $this->jobRepository = $jobRepository;
        $this->jobClassificationService = $jobClassificationService;
    }

    public function all(): Collection|array
    {
        return $this->jobRepository->all();
    }

    public function searchJobs(array $params): Collection|array
    {
        // Handle searching logic and any complex operations
        return $this->jobRepository->search($params);
    }

    public function findById(array $ids): ?Collection
    {
        return $this->jobRepository->findById($ids);
    }
}
