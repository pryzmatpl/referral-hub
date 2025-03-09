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

    public function findById(array $params): ?Job
    {
        return $this->jobRepository->findById($params["id"]);
    }

    /**
     * @throws \Exception
     */
    public function createJob(array $data): Job {
        // Get UserDB info
        $uid = User::query()->where('unique_id',$data['unique_id'])->first();

        // Validate data and create the job
        $job = new Job();
        $job->posterId = $uid->id;
        $job->fill($data);
        // Set other properties...
        $job->save();

        // Call the method to calculate the weight
        $jsonWeights = $this->calculateWeight($job);
        $jobWeight = new JobWeight(
            [
                'jobid' => $job->id,
                'keywords' => $job->keywords,
                'weights' => $jsonWeights
            ]
        );
        $jobWeight->save();


        return $job;
    }

    /**
     * @throws \Exception
     */
    public function updateJob(int $id, array $data, User $user): Job {
        $job = $this->jobRepository->findById($id);

        if (!$job) {
            throw new \Exception('Job not found.');
        }

        if (!$user->is_admin && $user->id != $job->user_id) {
            throw new \Exception('Permission denied.');
        }

        // Update the job details...
        // @TODO
        $job->save();

        $jsonWeights = $this->calculateWeight($job);
        $jobWeight = JobWeight::query()->get(["jobid" => $job->id]);
        $jobWeight->weights = $jsonWeights;
        $jobWeight->save();


        return $job;
    }

    /**
     * @throws \Exception
     */
    private function calculateWeight(Job $job): array
    {
        $keywords = explode(",", $job->keywords);
        return $this->jobClassificationService->classifyJob($keywords);
    }
}
