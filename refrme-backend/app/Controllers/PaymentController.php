<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 31.03.2025, 19:34
 * PaymentController.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class PaymentController
{
    public function createPaymentIntent(Request $request, Response $response): Response
    {
        // Parse the incoming request body
        $body = json_decode($request->getBody(), true);

        // Extract amount and currency from the request
        $amount = $body['amount'] ?? null;
        $currency = $body['currency'] ?? 'usd'; // Default to USD if not provided

        if (!$amount) {
            $response->withStatus(400)->getBody()->write(json_encode(['error' => 'Amount is required']));
            return $response;
        }

        try {
            // Create a new Customer
            $customer = \Stripe\Customer::create([
                'email' => $body['email'],
                'name' => $body['name'],
            ]);

            // Create a PaymentIntent with the customer ID
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'customer' => $customer->id,
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            // Respond with the client secret
            $response->getBody()->write(json_encode(['clientSecret' => $paymentIntent->client_secret]));
            return $response;
        } catch (ApiErrorException $e) {
            // Handle errors from the Stripe API
            $response->withStatus(500)->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response;
        }
    }
}