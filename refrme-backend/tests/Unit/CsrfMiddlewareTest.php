<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 09.03.2025, 18:37
 * CsrfMiddlewareTest.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */
namespace Tests\Unit;

use App\Middleware\CsrfViewMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\Psr7\Response;

class CsrfViewMiddlewareTest extends TestCase
{
    public function testProcessAddsCsrfGlobalAndDelegates()
    {
        // Create a stub for the CSRF service with the expected methods.
        $csrfStub = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['getTokenNameKey', 'getTokenName', 'getTokenValueKey', 'getTokenValue'])
            ->getMock();
        $csrfStub->expects($this->once())
            ->method('getTokenNameKey')
            ->willReturn('csrf_name');
        $csrfStub->expects($this->once())
            ->method('getTokenName')
            ->willReturn('testTokenName');
        $csrfStub->expects($this->once())
            ->method('getTokenValueKey')
            ->willReturn('csrf_value');
        $csrfStub->expects($this->once())
            ->method('getTokenValue')
            ->willReturn('testTokenValue');

        // Create a mock for the view environment which should have an addGlobal() method.
        $environmentMock = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['addGlobal'])
            ->getMock();
        $environmentMock->expects($this->once())
            ->method('addGlobal')
            ->with(
                'csrf',
                $this->callback(function ($data) {
                    $expected = sprintf(
                        '<input type="hidden" name="%s" value="%s">' .
                        '<input type="hidden" name="%s" value="%s">',
                        'csrf_name',
                        'testTokenName',
                        'csrf_value',
                        'testTokenValue'
                    );
                    return isset($data['field']) && $data['field'] === $expected;
                })
            );

        // Create a stub for the view that returns our environment mock.
        $viewStub = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['getEnvironment'])
            ->getMock();
        $viewStub->expects($this->once())
            ->method('getEnvironment')
            ->willReturn($environmentMock);

        // Create a fake container with both csrf and view services.
        $container = new \stdClass();
        $container->csrf = $csrfStub;
        $container->view = $viewStub;

        // Create a dummy request.
        $requestStub = $this->createMock(ServerRequestInterface::class);

        // Create a dummy response that the handler will return.
        $dummyResponse = new Response();

        // Create a handler stub that should be called with the request.
        $handlerStub = $this->getMockBuilder(RequestHandlerInterface::class)
            ->getMock();
        $handlerStub->expects($this->once())
            ->method('handle')
            ->with($requestStub)
            ->willReturn($dummyResponse);

        // Instantiate the middleware with our container.
        $middleware = new CsrfViewMiddleware($container);

        // Execute the middleware's process() method.
        $resultResponse = $middleware->process($requestStub, $handlerStub);

        // Assert that the middleware returns the response from the handler.
        $this->assertSame($dummyResponse, $resultResponse);
    }
}
