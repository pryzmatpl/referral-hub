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
use Illuminate\Database\Capsule\Manager as Capsule;
use Aurmil\Slim\CsrfTokenToView;
use Aurmil\Slim\CsrfTokenToHeaders;
use Slim\Http\Request;
use Slim\Csrf\Guard;
use Slim\Http\Response;
use App\Middleware\PrizmMiddleware;
use Slim\Middleware\Session;

require __DIR__ . '/../vendor/autoload.php';

$capsule = new Capsule();
// Load environment variables
try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
    $config = [
        'driver' => $_ENV['DB_DRIVER'],
        'host' => $_ENV['DB_HOST'],
        'database' => $_ENV['DB_DATABASE'],
        'username' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD'],
        'charset' => 'utf8',
        'port' => $_ENV['DB_PORT'],
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    ];

    $capsule->addConnection($config);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();
} catch (InvalidPathException $e) {
    throw new Exception("Failed initialize");
}

// Create Container Builder
$containerBuilder = new ContainerBuilder();

// Add container definitions
$definitions = require __DIR__ . '/container.php';
$definitions($containerBuilder);
$container = $containerBuilder->build();

AppFactory::setContainer(container: $container);
$app = AppFactory::create();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);


// Validators
$usernameValidator = v::alnum()->noWhitespace()->length(1, 15);
$ageValidator = v::numeric()->positive()->between(1, 20);

$app->add(new Session([
    'name' => 'prizm_session',
    'autorefresh' => true,
    'lifetime' => '1 hour',
]));

require_once __DIR__ . '/oauth2.php';

Router::registerRoutes($app);

return $app;