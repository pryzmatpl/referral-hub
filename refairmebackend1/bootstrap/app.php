<?php
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
use Illuminate\Database\Capsule\Manager as Capsule;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\IntrospectionProcessor;

#use Illuminate\Events\Dispatcher;

$container = new Container();
$capsule = new Capsule;

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
    error_log('Could not load .env file: ' . $e->getMessage());
}

// Register globally to app
$container->set('session', function () {
    return new Helper();
});

// Create Slim App instance ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$app = AppFactory::create(container:$container);
$app->addRoutingMiddleware();
Router::registerRoutes($app);

// Set up the logger
$container->set('logger', function () {
    $logger = new Logger('app');

    // You can add various processors to add more contextual information to logs
    $logger->pushProcessor(new UidProcessor());
    $logger->pushProcessor(new MemoryUsageProcessor());
    $logger->pushProcessor(new IntrospectionProcessor(Logger::DEBUG, ['App\\']));

    // Stream handler to output logs to a file
    $logFile = __DIR__ . '/../logs/app.log';
    $streamHandler = new StreamHandler($logFile, Logger::DEBUG);
    $logger->pushHandler($streamHandler);

    return $logger;
});

// Register Middleware
$app->add(new Session([
    'name' => 'prizm_session',
    'autorefresh' => true,
    'lifetime' => '1 hour',
]));

// Error Handling Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$container->set('settings', function () {
    return [
        'displayErrorDetails' => true,
        'mailer' => [
            'host' => $_ENV('MAIL_HOST'),
            'username' => $_ENV('MAIL_USERNAME'),
            'password' => $_ENV('MAIL_PASSWORD'),
            'port' => $_ENV('MAIL_PORT'),
            'secure' => $_ENV('MAIL_SECURE'),
            'context' => strpos($_ENV('MAIL_HOST'), '@gmail.com') !== false ? [
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

$container->set('csrf', function () {
    return new Guard();
});

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(function (
    Psr\Http\Message\ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($container) {
    $logger = $container->get('logger');
    $logger->error($exception->getMessage(), ['exception' => $exception]);
    $response = new Slim\Psr7\Response();
    $response->getBody()->write('An error occurred');
    return $response->withStatus(500);
});

// Run the application
$app->run();
