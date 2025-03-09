<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 09.03.2025, 13:34
 * JobControllerCest.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */
namespace Tests\Acceptance;
use Tests\Support\AcceptanceTester;
use Codeception\Util\HttpCode;

class JobControllerCest
{
    public function _before(AcceptanceTester $I)
    {
        // Ensure a clean state for jobs and users.
        $I->truncateTable('jobs');
        $I->truncateTable('users');
    }

    /**
     * Test retrieving all jobs.
     *
     * This test seeds two jobs and then calls GET /jobs?logic=all.
     */
    public function testGetAllJobs(AcceptanceTester $I)
    {
        // Seed sample jobs.
        $I->haveInDatabase('jobs', [
            'id'    => 1,
            'title' => 'Job One',
            // other fields as needed
        ]);
        $I->haveInDatabase('jobs', [
            'id'    => 2,
            'title' => 'Job Two',
        ]);

        // Request all jobs.
        $I->sendGET('/jobs', ['logic' => 'all']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        // Decode and assert that two jobs are returned.
        $jobs = json_decode($I->grabResponse(), true);
        $I->assertCount(2, $jobs);
    }

    /**
     * Test retrieving a job by its id.
     *
     * This test seeds one job and then calls GET /jobs?id=1.
     */
    public function testGetJobById(AcceptanceTester $I)
    {
        // Seed a job.
        $I->haveInDatabase('jobs', [
            'id'    => 1,
            'title' => 'Job One',
        ]);

        // Request the job with id=1.
        $I->sendGET('/jobs', ['id' => 1]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['id' => 1, 'title' => 'Job One']);
    }

    /**
     * Test searching jobs.
     *
     * Here we pass a search parameter (e.g. title) that triggers the searchJobs method.
     */
    public function testSearchJobs(AcceptanceTester $I)
    {
        // Seed sample jobs.
        $I->haveInDatabase('jobs', [
            'id'    => 1,
            'title' => 'Developer',
        ]);
        $I->haveInDatabase('jobs', [
            'id'    => 2,
            'title' => 'Designer',
        ]);

        // Call GET /jobs with a parameter (e.g., title=Developer).
        // (Depending on your jobService, the search may filter or return all jobs.)
        $I->sendGET('/jobs', ['title' => 'Developer']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['title' => 'Developer']);
    }

    /**
     * Test adding a new job.
     *
     * This test sends a JSON payload to POST /jobs/add and expects a success response.
     */
    public function testAddJob(AcceptanceTester $I)
    {
        // Ensure the jobs table is empty.
        $I->truncateTable('jobs');

        // Prepare the JSON payload for creating a job.
        $jobData = [
            'title'       => 'New Job',
            'description' => 'Job description here',
            // Add any other fields expected by your JobService.
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/jobs/add', json_encode($jobData));
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status'  => 'success',
            'message' => 'Successfully added job'
        ]);

        // Optionally, verify the job was created in the database.
        $I->seeInDatabase('jobs', ['title' => 'New Job']);
    }

    /**
     * Test updating an existing job.
     *
     * This test simulates a logged-in user, seeds a job, and then sends a PUT request to update it.
     */
    public function testUpdateJob(AcceptanceTester $I)
    {
        // Seed a job record.
        $I->haveInDatabase('jobs', [
            'id'          => 1,
            'title'       => 'Old Job Title',
            'description' => 'Old description',
        ]);

        // Seed a user record and simulate a login.
        $I->haveInDatabase('users', [
            'id'    => 1,
            'email' => 'user@example.com',
            'name'  => 'Test User',
        ]);
        $I->amOnPage('/test-login?user_id=1');

        // Prepare updated job data.
        $updateData = [
            'title'       => 'Updated Job Title',
            'description' => 'Updated description',
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        // Assuming your update route is defined as /jobs/update/{id}.
        $I->sendPUT('/jobs/update/1', json_encode($updateData));
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status'  => 'success',
            'message' => 'Successfully updated job'
        ]);

        // Verify the database record was updated.
        $I->seeInDatabase('jobs', [
            'id'          => 1,
            'title'       => 'Updated Job Title',
            'description' => 'Updated description',
        ]);
    }
}
