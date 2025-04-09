<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 09.03.2025, 18:18
 * AuthMiddlewareTest.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */
namespace Tests\Unit;

use App\Http\HttpCodes;
use App\Middleware\AuthMiddleware;
use App\Services\Auth\AuthService;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddlewareTest extends TestCase
{
    public function testProcessUnauthorized(): void
    {
        // Create an AuthService stub that returns false for check()
        $authServiceStub = $this->createMock(AuthService::class);
        $authServiceStub->method('check')->willReturn(false);

        // Create a ResponseFactory stub that returns a new Response with a 403 status code
        $expectedResponse = new Response(HttpCodes::HTTP_FORBIDDEN);
        $responseFactoryStub = $this->createMock(ResponseFactoryInterface::class);
        $responseFactoryStub->method('createResponse')
            ->with(HttpCodes::HTTP_FORBIDDEN)
            ->willReturn($expectedResponse);

        $middleware = new AuthMiddleware($authServiceStub, $responseFactoryStub);

        // Create a dummy request (the actual values are not important here)
        $requestStub = $this->createMock(ServerRequestInterface::class);

        // Create a handler stub which should never be called when unauthorized.
        $handlerStub = $this->createMock(RequestHandlerInterface::class);
        $handlerStub->expects($this->never())
            ->method('handle');

        // Execute middleware process()
        $response = $middleware->process($requestStub, $handlerStub);

        // Assert that the response status code is 403
        $this->assertEquals(HttpCodes::HTTP_FORBIDDEN, $response->getStatusCode());

        // Assert that the JSON body contains the expected error message
        $body = (string)$response->getBody();
        $data = json_decode($body, true);
        $this->assertEquals("Please Sign In", $data['message']);
        $this->assertEquals("error", $data['status']);
    }

    public function testProcessAuthorized(): void
    {
        // Create an AuthService stub that returns true for check()
        $authServiceStub = $this->createMock(AuthService::class);
        $authServiceStub->method('check')->willReturn(true);

        // The ResponseFactory is not used when authorized, so a dummy mock is enough.
        $responseFactoryStub = $this->createMock(ResponseFactoryInterface::class);

        $middleware = new AuthMiddleware($authServiceStub, $responseFactoryStub);

        // Create a dummy request
        $requestStub = $this->createMock(ServerRequestInterface::class);

        // Create a dummy response to be returned from the handler.
        $dummyResponse = new Response(200);

        // Create a handler stub that returns the dummy response.
        $handlerStub = $this->createMock(RequestHandlerInterface::class);
        $handlerStub->expects($this->once())
            ->method('handle')
            ->with($requestStub)
            ->willReturn($dummyResponse);

        // Execute middleware process()
        $response = $middleware->process($requestStub, $handlerStub);

        // Assert that the response from the handler is returned.
        $this->assertSame($dummyResponse, $response);
    }
}
