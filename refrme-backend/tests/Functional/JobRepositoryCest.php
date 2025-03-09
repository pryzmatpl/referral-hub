<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 09.03.2025, 13:24
 * JobRepositoryCest.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */
namespace App\Tests\Functional;

use App\Repositories\JobRepository;
use Codeception\Example;
use Illuminate\Database\Eloquent\Collection;
use FunctionalTester;

class JobRepositoryCest
{
    /**
     * Clean up the database before each test.
     *
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        // Truncate the jobs and companies tables to ensure a clean slate.
        $I->truncateTable('jobs');
        $I->truncateTable('companies');
    }

    /**
     * Test that all() returns all job records.
     *
     * @param FunctionalTester $I
     */
    public function testAllJobs(FunctionalTester $I)
    {
        // Insert sample job records using Codeception helper.
        $I->haveInDatabase('jobs', [
            'id'   => 1,
            'name' => 'Job One',
        ]);
        $I->haveInDatabase('jobs', [
            'id'   => 2,
            'name' => 'Job Two',
        ]);

        $repository = new JobRepository();
        $jobs = $repository->all();

        $I->assertInstanceOf(Collection::class, $jobs);
        $I->assertEquals(2, $jobs->count());
    }

    /**
     * Test that search() returns jobs with only the specified columns.
     *
     * @param FunctionalTester $I
     */
    public function testSearchJobs(FunctionalTester $I)
    {
        // Insert sample job records.
        $I->haveInDatabase('jobs', [
            'id'   => 1,
            'name' => 'Job One',
            'description' => 'Description one',
        ]);
        $I->haveInDatabase('jobs', [
            'id'   => 2,
            'name' => 'Job Two',
            'description' => 'Description two',
        ]);

        $repository = new JobRepository();
        // Pass an array of columns to fetch only these columns.
        $jobs = $repository->search(['id', 'name']);

        $I->assertInstanceOf(Collection::class, $jobs);
        $I->assertNotEmpty($jobs);
        foreach ($jobs as $job) {
            $attributes = $job->getAttributes();
            $I->assertArrayHasKey('id', $attributes);
            $I->assertArrayHasKey('name', $attributes);
            // Ensure other columns are not present.
            $I->assertArrayNotHasKey('description', $attributes);
        }
    }

    /**
     * Test that findById() returns a job with its associated company.
     *
     * @param FunctionalTester $I
     */
    public function testFindById(FunctionalTester $I)
    {
        // Insert a sample company record.
        $I->haveInDatabase('companies', [
            'id'   => 100,
            'name' => 'Test Company',
        ]);

        // Insert a sample job that references the company.
        // Adjust the foreign key column as per your actual schema (e.g., companies_id).
        $I->haveInDatabase('jobs', [
            'id'           => 1,
            'name'         => 'Job One',
            'companies_id' => 100,
        ]);

        $repository = new JobRepository();
        $job = $repository->findById(1);

        $I->assertNotNull($job);
        $I->assertEquals(1, $job->id);
        $I->assertEquals('Job One', $job->name);
        // The job should have its "company" relationship loaded
        $I->assertNotNull($job->company);
        $I->assertEquals('Test Company', $job->company->name);
    }
}
