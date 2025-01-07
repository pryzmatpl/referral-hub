<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 07.01.2025, 21:17
 * app.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */

use App\Router;
use DavidePastore\Slim\Validation\Validation;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Slim\Factory\AppFactory;
use Respect\Validation\Validator as v;
use Aurmil\Slim\CsrfTokenToView;
use Aurmil\Slim\CsrfTokenToHeaders;
use Slim\Http\Request;
use Slim\Csrf\Guard;
use Slim\Http\Response;
use App\Middleware\PrizmMiddleware;
use Slim\Middleware\Session;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (InvalidPathException $e) {
    throw new Exception("Failed to load environment");
}

// Create Container Builder
$containerBuilder = new ContainerBuilder();

// Add container definitions
$definitions = require __DIR__ . '/container.php';
$definitions($containerBuilder);
$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();
// Create App instance

// Validators
$usernameValidator = v::alnum()->noWhitespace()->length(1, 15);
$ageValidator = v::numeric()->positive()->between(1, 20);
$validators = [
    'username' => $usernameValidator,
];

// Register middleware
$app->add(new Validation($validators));
$app->add(new Session([
    'name' => 'prizm_session',
    'autorefresh' => true,
    'lifetime' => '1 hour',
]));
$app->add(function ($request, $response, $next) {
    $route = $request->getAttribute("route");
    $methods = $route ? array_merge_recursive([], $route->getMethods()) : [$request->getMethod()];
    $response = $next($request, $response);
    return $response->withHeader("Access-Control-Allow-Methods", implode(",", $methods))
        ->withHeader("Access-Control-Allow-Origin", '*');
});

require_once __DIR__ . '/oauth2.php';

Router::registerRoutes($app);

return $app;