<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 31.03.2025, 21:02
 * PaymentControllerCest.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */

namespace Tests\Functional;

use App\Controllers\PaymentController;
use Codeception\Util\HttpCode;
use Tests\Support\FunctionalTester;

class PaymentControllerCest
{

    public function testCreatePaymentIntent(FunctionalTester $I)
    {
        // Simulate an authenticated session
        $I->amHttpAuthenticated('testuser@example.com', 'password'); // optional if using basic auth
        $I->haveHttpHeader('Content-Type', 'application/json');

        // Mock session user (if your app uses $_SESSION['user'] as Stripe customer ID)
        $I->setCookie('PHPSESSID', 'testing-session');
        $_SESSION['user'] = 'cus_test_123'; // mock customer ID

        // Prepare a fake payment intent creation request
        $payload = [
            'amount' => 5000,
            'currency' => 'usd'
        ];

        $I->sendPOST('/create-payment-intent', json_encode($payload));

        // Assert that the response is successful
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        // Check for clientSecret in the response
        $I->seeResponseMatchesJsonType([
            'clientSecret' => 'string'
        ]);
    }
}
