<?php
/**
 * This code is part of referalhub
 * 2024 08 08 18 34
 */
namespace App\Services;

use App\Repositories\JobRepository;
use App\Models\Job;
use App\Models\User;

class JobService {
    private $jobRepository;

    public function __construct(JobRepository $jobRepository) {
        $this->jobRepository = $jobRepository;
    }

    public function all() {
        return $this->jobRepository->all();
    }

    public function searchJobs(array $params) {
        // Handle searching logic and any complex operations
        return $this->jobRepository->search($params);
    }

    public function findById(array $params) {
        return $this->jobRepository->findById($params["id"]);
    }

    public function createJob(array $data): Job {
        // Get UserDB info
        $uid = User::where('unique_id',$data['unique_id'])->first();

        // Validate data and create the job
        $job = new Job();
        $job->posterId = $uid->id;
        $job->fill($data);
        // Set other properties...
        $job->save();

        // Call the method to calculate the weight
        $this->calculateWeight($job);

        return $job;
    }

    public function updateJob(int $id, array $data, User $user): Job {
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

    private function calculateWeight(Job $job) {
        // Implement your weight calculation logic here
    }
}
