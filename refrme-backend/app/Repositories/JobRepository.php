<?php
/**
 * This code is part of referalhub
 * All rights reserved
 * 2024 08 08 18 32
 */
namespace App\Repositories;

use App\Models\Job;
use App\Models\JobWeight;
use App\Models\User;
use App\Services\JobClassificationService;
use App\Services\JobService;
use Codeception\Extension\Logger;
use Illuminate\Database\Eloquent\Collection;
use Psr\Log\LoggerInterface;

class JobRepository {
    private LoggerInterface $logger;
    private JobClassificationService $jobClassificationService;
    public function __construct(LoggerInterface $logger, JobClassificationService $jobClassificationService)
    {
        $this->logger=$logger;
        $this->jobClassificationService=$jobClassificationService;
    }

    public function all(): Collection|array
    {
        return Job::all();
    }

    public function search(array $params): Collection|array
    {
        $query = Job::query();

        if (isset($params['salary_min'])) {
            $salaryMin = (int) $params['salary_min'];
    
            $query->whereRaw("CAST(SUBSTRING_INDEX(fund, ',', 1) AS UNSIGNED) <= ?", [$salaryMin])
                  ->whereRaw("CAST(SUBSTRING_INDEX(fund, ',', -1) AS UNSIGNED) >= ?", [$salaryMin]);
        }

        if (isset($params["relocation"])) {
            $query->where("relocation", $params["relocation"]);
        }

        if (isset($params["remote"])) {
            $query->where("remote", $params["remote"]);
        }
    
        return $query->get();
    }

    public function findById(array|int $ids): ?Collection {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        return Job::wherein("id", $ids)->get();
    }

    public function save(array $params): Job
    {
        try {
            return $this->createJob($params);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return Job::InvalidJob();
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

        $this->logger->debug(json_encode($data));

        $arrOfSkills = [];

        if (isset($data['skills']) && is_array($data['skills'])) {
            foreach ($data['skills'] as $skill) {
                // Access each skill's details; here, we print the name, experience, and years
                $arrOfSkills[] = $skill["name"];
            }
        } else {
            echo "No skills found.";
        }

        $jsonWeights = $this->jobClassificationService->classifyJob($arrOfSkills);

        $jobWeight = new JobWeight(
            [
                'jobid' => $job->id,
                'keywords' => $job->keywords,
                'weights' => json_encode($jsonWeights)
            ]
        );
        $jobWeight->save();


        return $job;
    }

    /**
     * @throws \Exception
     */
    public function updateJobWeights(int $id, array $keywords, User $user): Job {
        $job = $this->findById($id);

        if (!$job) {
            throw new \Exception('Job not found.');
        }

        if (!$user->is_admin && $user->id != $job->user_id) {
            throw new \Exception('Permission denied.');
        }

        // Update the job details...
        // @TODO
        $job->save();

        $jsonWeights = $this->jobClassificationService->classifyJob($keywords);
        $jobWeight = JobWeight::query()->get(["jobid" => $job->id]);
        $jobWeight->weights = json_encode($jsonWeights);
        $jobWeight->save();


        return $job;
    }

}
