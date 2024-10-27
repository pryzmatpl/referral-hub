<?php

namespace App\Middleware;
use App\Models\Activity;
/**
 *
 */
class ActivityMiddleware extends Middleware
{
    public function __invoke($request,$response,$next)
    {
        if($this->container->auth->check()) {
            $act = Activity::create(['request'=>$request]);
        }

        $response = $next($request,$response);
        return $response;
    }
}