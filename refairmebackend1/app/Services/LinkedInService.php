<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 14.01.2025, 21:32
 * LinkedInService.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

final class LinkedInService
{
    public function __construct(
        private readonly Client $httpClient,
        private readonly string $clientId,
        private readonly string $clientSecret
    ) {}

    /**
     * @throws GuzzleException
     */
    public function getAccessToken(
        string $code,
        string $tokenUrl,
        string $redirectUri
    ): array {
        $response = $this->httpClient->post($tokenUrl, [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $redirectUri,
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        $data = $this->decodeResponse($response->getBody()->getContents());

        if (!isset($data['access_token'])) {
            throw new RuntimeException('Invalid LinkedIn response: access token not found');
        }

        return $data;
    }

    /**
     * @throws GuzzleException
     */
    public function getUserInfo(string $accessToken, string $userInfoUrl): array
    {
        $response = $this->httpClient->get($userInfoUrl, [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = $this->decodeResponse($response->getBody()->getContents());

        if (!isset($data['sub'])) {
            throw new RuntimeException('Invalid LinkedIn response: user info not found');
        }

        return $data;
    }

    /**
     * @throws RuntimeException
     */
    private function decodeResponse(string $response): array
    {
        $data = json_decode($response, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException('Failed to decode LinkedIn response: ' . json_last_error_msg());
        }

        return $data;
    }
}