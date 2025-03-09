<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 09.03.2025, 13:28
 * ReferralControllerAcceptanceCest.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */

namespace Tests\Acceptance;
use Tests\Support\AcceptanceTester;
use Codeception\Util\HttpCode;

class ReferralControllerAcceptanceCest
{
    /**
     * Test the GET /referral endpoint.
     *
     * This method calls the controller's get() method, which returns
     * a JSON response with the value of cc(array('1')).
     *
     * @param AcceptanceTester $I
     */
    public function testGetReferral(AcceptanceTester $I)
    {
        $I->amOnPage('/referral');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        // Assuming cc(array('1')) returns an array with element "1"
        $I->seeResponseContainsJson(["1"]);
    }

    /**
     * Test the GET /referral/send/{user} endpoint.
     *
     * This test creates a user and an associated referral record,
     * then calls the endpoint using the user's email.
     *
     * @param AcceptanceTester $I
     */
    public function testGetReferralSend(AcceptanceTester $I)
    {
        // Insert a test user
        $I->haveInDatabase('users', [
            'id'    => 1,
            'email' => 'test@example.com',
            'name'  => 'Test User',
        ]);

        // Insert a referral for this user
        $I->haveInDatabase('jobs_referral', [
            'id'         => 10,
            'jobs_id'    => 1,
            'users_id'   => 1,
            'email'      => 'referral@example.com',
            'name'       => 'Referral Test',
            'status'     => 'new',
            'hash'       => 'dummyhash',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $I->sendGET('/referral/send/test@example.com');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        // Verify that the referral data is returned
        $I->seeResponseContainsJson([
            'email' => 'referral@example.com',
            'name'  => 'Referral Test',
        ]);
    }

    /**
     * Test the GET /referral/received/{email} endpoint.
     *
     * This test inserts a referral record using a specific email,
     * then calls the endpoint and verifies the returned JSON.
     *
     * @param AcceptanceTester $I
     */
    public function testGetReferralReceived(AcceptanceTester $I)
    {
        // Insert a referral with a known email
        $I->haveInDatabase('jobs_referral', [
            'id'         => 20,
            'jobs_id'    => 1,
            'users_id'   => 2,
            'email'      => 'received@example.com',
            'name'       => 'Referral Received',
            'status'     => 'new',
            'hash'       => 'dummyhash',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $I->sendGET('/referral/received/received@example.com');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        // Verify that the referral record is returned
        $I->seeResponseContainsJson([
            'email' => 'received@example.com',
            'name'  => 'Referral Received',
        ]);
    }

    /**
     * Test the POST /referral/add endpoint.
     *
     * This test simulates adding a referral.
     * It first creates the necessary user, job, and company records,
     * then simulates a login and sends a POST request.
     *
     * @param AcceptanceTester $I
     */
    public function testAddReferral(AcceptanceTester $I)
    {
        // Prepare test data in the database.
        // Create a test user.
        $I->haveInDatabase('users', [
            'id'    => 1,
            'email' => 'user@example.com',
            'name'  => 'Test User',
        ]);

        // Create a job (using the job_desc table) that references a company.
        $I->haveInDatabase('job_desc', [
            'id'        => 1,
            'title'     => 'Job Test',
            'companyId' => 100,
        ]);

        // Create a company record.
        $I->haveInDatabase('companies', [
            'id'   => 100,
            'name' => 'Test Company',
        ]);

        // Simulate a logged-in user.
        // This assumes you have a test route to set session data for testing.
        // Alternatively, you can set the session cookie manually.
        $I->amOnPage('/test-login?user_id=1');

        // Prepare the POST data.
        $postData = [
            'job_id' => 1,
            'email'  => 'referral@example.com',
            'name'   => 'Referral Person',
        ];

        $I->sendPOST('/referral/add', $postData);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        // Verify that the response indicates a successful addition.
        $I->seeResponseContainsJson([
            'status'  => 'success',
            'message' => 'Successfully added referral for job #1'
        ]);
    }
}
