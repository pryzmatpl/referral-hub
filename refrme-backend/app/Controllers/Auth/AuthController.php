<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 13.01.2025, 21:16
 * AuthController.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */
namespace App\Controllers\Auth;

use App\Http\HttpCodes;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\LinkedInService;
use App\Services\UserService;
use App\Validation\Exceptions\UserDoesNotExistException;
use App\Validation\Validator;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Monolog\Logger;
use Nette\Mail\Mailer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;

final class AuthController
{
    private const LINKEDIN_SCOPE = 'openid profile email';
    private const LINKEDIN_ACCESS_TOKEN_URL = 'https://www.linkedin.com/oauth/v2/accessToken';
    private const LINKEDIN_USERINFO_URL = 'https://api.linkedin.com/v2/userinfo';
    const USER_DOES_NOT_EXIST = 'user does not exist';
    const UNIQUE_ID_IS_REQUIRED = 'Unique ID is required';

    public function __construct(
        private readonly AuthService     $auth,
        private readonly Validator       $validator,
        private readonly Mailer          $mailer,
        private readonly Logger          $logger,
        private readonly UserService     $userService,
        private readonly LinkedInService $linkedInService,
        private readonly Client          $httpClient
    ) {}

    public function signOut(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->auth->logout();

        return $this->jsonResponse($response, [
            'state' => 'success',
            'message' => 'You have been logged out'
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function getLinkedInAccessToken(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $code = $this->getRequiredParam($request, 'code');
        $redirectUrl = sprintf("%s/%s", getenv("FRONTEND_URL"), "auth/signin");
        try {
            $token = $this->linkedInService->getAccessToken(
                $code,
                self::LINKEDIN_ACCESS_TOKEN_URL,
                $redirectUrl
            );

            return $this->jsonResponse($response, $token);
        } catch (Exception $e) {
            return $this->invalidLoginResponse($response, 'Failed to fetch LinkedIn access token', $e);
        }
    }

    /**
     * @throws GuzzleException
     */
    public function getLinkedInUserInfo(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $accessToken = $this->getRequiredParam($request, 'access_token');
            $userInfo = $this->linkedInService->getUserInfo($accessToken, self::LINKEDIN_USERINFO_URL);
            return $this->jsonResponse($response, $userInfo);
        } catch (Exception $e) {
            return $this->invalidLoginResponse($response, 'Failed to fetch LinkedIn user info', $e);
        }
    }

    public function signIn(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $payload = $this->getJsonPayload($request);
            $uniqueId = $payload['params']['uniqueId'] ?? null;

            if (!$uniqueId) {
                throw new RuntimeException(self::UNIQUE_ID_IS_REQUIRED);
            }

            $user = User::where('unique_id', $uniqueId)->first();

            if (empty($user)) {
                throw new UserDoesNotExistException(self::USER_DOES_NOT_EXIST);
            }

            $roles = $this->userService->getUserRoles($user);

            $_SESSION['user'] = $user;
            $_SESSION['user_roles'] = $roles;

            return $this->jsonResponse($response, [
                'state' => 'success',
                'auth' => true
            ]);
        } catch (Exception $e) {
            return $this->invalidLoginResponse($response, 'Authentication failed', $e);
        }
    }

    public function signUp(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            if (!$this->validateSignUpRequest($request)) {
                return $this->jsonResponse($response, [
                    'state' => 'error',
                    'message' => 'Validation Failed - Email taken or in invalid format.'
                ], HttpCodes::HTTP_BAD_REQUEST);
            }

            $user = $this->userService->createUser($this->getJsonPayload($request));

            return $this->jsonResponse($response, [
                'message' => 'Successful Registration!',
                'state' => 'success'
            ]);
        } catch (Exception $e) {
            return $this->invalidLoginResponse($response, 'Registration failed', $e);
        }
    }

    public function confirmEmail(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $code = $request->getQueryParams()['code'] ?? null;

            if (!$code) {
                throw new RuntimeException('No activation code provided');
            }

            $this->userService->activateUser($code);

            return $this->jsonResponse($response, [
                'message' => 'Your signup is successful. You can sign in now!'
            ]);
        } catch (Exception $e) {
            return $this->invalidLoginResponse($response, 'Email confirmation failed', $e);
        }
    }

    private function validateSignUpRequest(ServerRequestInterface $request): bool
    {
        // Implement your validation logic here
        return true;
    }

    private function getJsonPayload(ServerRequestInterface $request): array
    {
        $payload = json_decode((string) $request->getBody(), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException('Invalid JSON payload');
        }

        return $payload;
    }

    private function getRequiredParam(ServerRequestInterface $request, string $param): string
    {
        $payload = $this->getJsonPayload($request);

        if (!isset($payload[$param])) {
            throw new RuntimeException("Missing required parameter: {$param}");
        }

        return $payload[$param];
    }

    /**
     * @param ResponseInterface $response
     * @param array $data
     * @param int $status
     * @return ResponseInterface
     */
    private function jsonResponse(
        ResponseInterface $response,
        array $data,
        int $status = HttpCodes::HTTP_OK
    ): ResponseInterface {
        $response->getBody()->write(json_encode($data));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }

    private function invalidLoginResponse(
        ResponseInterface $response,
        string $message,
        Exception $exception
    ): ResponseInterface {
        $this->logger->error($message, [
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        return $this->jsonResponse($response, [
            'message' => $message,
            'error' => $exception->getMessage()
        ], HttpCodes::HTTP_FORBIDDEN);
    }
}