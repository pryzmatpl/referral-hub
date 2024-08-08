<?php
/**
 * This code is part of referalhub
 * 2024 08 08 18 34
 */
namespace App\Services;

use App\Repositories\JobRepository;
use App\Models\Jobdesc;
use App\Models\User;

class JobService {
    private $jobRepository;

    public function __construct(JobRepository $jobRepository) {
        $this->jobRepository = $jobRepository;
    }

    public function searchJobs(array $params) {
        // Handle searching logic and any complex operations
        return $this->jobRepository->search($params);
    }

    public function createJob(array $data, int $userId): Jobdesc {
        // Validate data and create the job
        $job = new Jobdesc();
        $job->user_id = $userId;
        // Set other properties...
        $job->save();

        // Call the method to calculate the weight
        $this->calculateWeight($job);

        return $job;
    }

    public function updateJob(int $id, array $data, User $user): Jobdesc {
        $job = $this->jobRepository->findById($id);

        if (!$job) {
            throw new \Exception('Job not found.');
        }

        if (!$user->is_admin && $user->id != $job->user_id) {
            throw new \Exception('Permission denied.');
        }

        // Update the job details...
        $job->save();

        $this->calculateWeight($job);

        return $job;
    }

    private function calculateWeight(Jobdesc $job) {
        // Implement your weight calculation logic here
    }
}
