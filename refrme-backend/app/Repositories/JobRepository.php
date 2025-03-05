<?php
/**
 * This code is part of referalhub
 * All rights reserved
 * 2024 08 08 18 32
 */
namespace App\Repositories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Collection;

class JobRepository {

    public function all(): Collection|array
    {
        return Job::all();
    }

    public function search(array $params) {
        $query = Job::query();

        if (isset($params["remote"])) {
            $query->where("remote", $params["remote"]);
        }

        if (isset($params['salary_min'])) {
            $salaryMin = (int) $params['salary_min'];
    
            $query->whereRaw("CAST(SUBSTRING_INDEX(fund, ',', 1) AS UNSIGNED) <= ?", [$salaryMin])
                  ->whereRaw("CAST(SUBSTRING_INDEX(fund, ',', -1) AS UNSIGNED) >= ?", [$salaryMin]);
        }

        if (isset($params["relocation"])) {
            $query->where("relocation", $params["relocation"]);
        }
    
        return $query->get();
    }

    public function findById(int $id): ?Job {
        return Job::with('company')->find($id);
    }
}
