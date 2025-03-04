<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 07.01.2025, 22:11
 * DataTransfomerTrait.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */
namespace App\Controllers;
use Psr\Http\Message\ResponseInterface;
use App\Http\HttpCodes;

/**
 * Main HTTP controller class
 */
class Controller
{

    protected function jsonResponse(
        ResponseInterface $response,
        array $data,
        int $status = HttpCodes::HTTP_OK
    ): ResponseInterface {
        $response->getBody()->write(json_encode($data));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}