<?php
namespace App\Middleware;

use App\Http\HttpCodes;
use App\Services\Auth\AuthService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Auth middleware
 */
class AuthMiddleware extends Middleware
{
    private AuthService $authService;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(AuthService $authService, ResponseFactoryInterface $responseFactory)
    {
        $this->authService = $authService;
        $this->responseFactory=$responseFactory;
    }

    public function process($request, $handler): ResponseInterface
    {
        if(!$this->authService->check() && false) { // @todo: DISABLE THIS FOR NOW! NEED TO REDESIGN PUBLIC/PRIVATE PAGES
            $response = $this->responseFactory->createResponse(HttpCodes::HTTP_FORBIDDEN);

            $response->getBody()->write(json_encode(
                [
                    'message'=>"Please Sign In",
                    'status'=>"error"
                ])
            );

            return $response;
        }

        return $handler->handle($request);
    }
}