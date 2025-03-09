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

    public function search(array $params): Collection|array
    {
        return Job::query()->get($params);
    }

    public function findById(int $id): ?Job {
        return Job::with('company')->find($id);
    }
}
