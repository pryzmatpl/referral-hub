<?php
/**
 * This code is part of referalhub
 * All rights reserved
 * 2024 08 08 18 32
 */
namespace App\Repositories;

use App\Models\Jobdesc;

class JobRepository {

    public function search(array $params) {
        // Implement your search logic using Eloquent or Query Builder
        return Jobdesc::where(/* conditions */)->get();
    }

    public function findById(int $id): ?Jobdesc {
        return Jobdesc::find($id);
    }

    // Additional methods for interacting with the Jobdesc model...
}
