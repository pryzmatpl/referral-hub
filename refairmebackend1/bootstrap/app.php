<?php

use App\Controllers\Auth\AuthController;
use App\Controllers\JobController;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Middleware\Session;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Psr\Container\ContainerInterface;
use App\Router;
use App\Validation\Validator;
use SlimSession\Helper;

$container = new Container();

// Load environment variables
try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (InvalidPathException $e) {
    error_log('Could not load .env file: ' . $e->getMessage());
}

// Register globally to app
$container->set('session', function () {
    return new Helper();
});

// Create Slim App instance
$app = AppFactory::create(container:$container);
$app->addRoutingMiddleware();

// Register Routes
Router::registerRoutes($app);

// Register Middleware
$app->add(new Session([
    'name' => 'prizm_session',
    'autorefresh' => true,
    'lifetime' => '1 hour',
]));

// Error Handling Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Settings and Dependencies
$container->set('settings', function () {
    return [
        'displayErrorDetails' => true,
        'mailer' => [
            'host' => getenv('MAIL_HOST'),
            'username' => getenv('MAIL_USERNAME'),
            'password' => getenv('MAIL_PASSWORD'),
            'port' => getenv('MAIL_PORT'),
            'secure' => getenv('MAIL_SECURE'),
            'context' => strpos(getenv('MAIL_HOST'), '@gmail.com') !== false ? [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ] : [],
        ],
    ];
});

$container->set('mailer', function (ContainerInterface $container) {
    return new Nette\Mail\SmtpMailer($container->get('settings')['mailer']);
});

$container->set('flash', function () {
    return new Messages();
});

$container->set('session', function () {
    return new Helper();
});

$container->set('validator', function () {
    return new Validator();
});

// Register CSRF Middleware
$container->set('csrf', function () {
    return new Guard();
});


// Run the application
$app->run();
