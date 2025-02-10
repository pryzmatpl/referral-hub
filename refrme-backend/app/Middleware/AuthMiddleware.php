<?php

namespace App\Middleware;

/**
 *
 */
class AuthMiddleware extends Middleware
{

    public function __invoke($request,$response,$next)
    {
        if(!$this->container->auth->check()) {
            return $response->withJson(['message'=>"Please Sign In Before Doing That",
                'status'=>"error"])->withStatus(401);
        }

        $response = $next($request,$response);
        return $response;

    }
}