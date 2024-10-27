<?php

use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Psr\Container\ContainerInterface;
use App\Middleware\CsrfViewMiddleware;
use App\Middleware\OldInputMiddleware;
use App\Middleware\ValidationErrorsMiddleware;
use App\Router;
use App\Validation\Validator;
use RKA\Middleware\IpAddress;
use SlimSession\Helper;
use SlimSession\Middleware as SessionMiddleware;
use Respect\Validation\Validator as v;


// Load environment variables
try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (InvalidPathException $e) {
    error_log('Could not load .env file: ' . $e->getMessage());
}

// Create Slim App instance
$app = AppFactory::create();
$app->addRoutingMiddleware();

// Create container
$container = $app->getContainer();

// Register Middleware
$app->add(new IpAddress(true));
$app->add(new SessionMiddleware([
    'name' => 'prizm_session',
    'autorefresh' => true,
    'lifetime' => '1 hour',
]));

$app->add(TwigMiddleware::createFromContainer($app, Twig::class));
$app->add(new ValidationErrorsMiddleware($container));
$app->add(new OldInputMiddleware($container));
$app->add(new CsrfViewMiddleware($container));

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

$container->set(Twig::class, function (ContainerInterface $container) {
    $twig = Twig::create(__DIR__ . '/../' . getenv('TWIG_TEMPLATES'), [
        'cache' => false,
    ]);

    // Add global variables
    $twig->getEnvironment()->addGlobal('auth', [
        'check' => $container->get('auth')->check(),
        'user' => $container->get('auth')->user(),
    ]);
    $twig->getEnvironment()->addGlobal('flash', $container->get('flash'));

    return $twig;
});

$container->set('validator', function () {
    return new Validator();
});

// Register Controllers
$container->set(HomeController::class, function (ContainerInterface $container) {
    return new HomeController($container);
});

// Register CSRF Middleware
$container->set('csrf', function () {
    return new Guard();
});

// Register Routes
Router::registerRoutes($app);

// Run the application
$app->run();
