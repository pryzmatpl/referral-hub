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
        // Implement your search logic using Eloquent or Query Builder
        $data = Job::where()->get($params);
        return $data;
    }

    public function findById(int $id): ?Job {
        return Job::find($id);
    }
}
