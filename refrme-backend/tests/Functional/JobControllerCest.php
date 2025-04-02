<?php

namespace Tests\Functional;

use Tests\Support\FunctionalTester;

class JobControllerCest
{

    // Test the /jobs endpoint with the 'logic=all' query parameter
    public function testGetAllJobs(FunctionalTester $I)
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
        $I->haveInDatabase('jobs', [
            'id'    => 3,
            'title' => 'UI',
        ]);

        $I->sendGET('/getjobs', ['logic' => 'all']);
        
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            ['title' => 'Developer']
        ]);
        $jobs = json_decode($I->grabResponse(), true);
        $I->assertCount(3, $jobs);
    }

    // Test the /jobs endpoint with the 'id' query parameter
    public function testGetJobById(FunctionalTester $I)
    {
        
        $I->haveInDatabase('jobs', [
            'id'    => 1,
            'title' => 'Something',
        ]);
        $I->haveInDatabase('jobs', [
            'id'    => 2,
            'title' => 'Else',
        ]);
        $I->haveInDatabase('jobs', [
            'id'    => 3,
            'title' => 'More',
        ]);

        $I->sendGET('/getjobs', ['id' => 2]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['id' => 2]);
        $I->seeResponseContainsJson([
            ['title' => 'Else']  
        ]);
        $jobs = json_decode($I->grabResponse(), true);
        $I->assertCount(1, $jobs);
    }

    // Test error handling in the controller
    public function testNonExistentId(FunctionalTester $I)
    {
        $I->sendGET('/getjobs', ['id' => 5]); 

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $jobs = json_decode($I->grabResponse(), true);
        $I->assertCount(0, $jobs);
    }

    // Test error handling in the controller
    public function testErrorHandling(FunctionalTester $I)
    {
        // Simulate a case where the jobService throws an exception by using a mock or triggering error
        $I->sendGET('/getjobs');  // You can force an error scenario by sending a request that triggers it

        // Assert the status code is 200
        $I->seeResponseCodeIs(200);

        // Assert the response contains the error message
        // Check if the 'error' key exists in the response
        $I->seeResponseJsonMatchesJsonPath('error');

    }
}
