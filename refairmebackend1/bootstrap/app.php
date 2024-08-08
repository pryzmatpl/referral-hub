<?php

use adrianfalleiro\SlimCLIRunner;
use App\Commands\FixCommand;
use App\Commands\ImportUsersCommand;
use App\Commands\ProfileEmailsCommand;
use App\Commands\ProfileSendCommand;
use App\Controllers\AdminController;
use App\Controllers\AppointmentsController;
use App\Controllers\Auth\AuthController;
use App\Controllers\Auth\PasswordController;
use App\Controllers\CartController;
use App\Controllers\ConsoleController;
use App\Controllers\CrawlerController;
use App\Controllers\HomeController;
use App\Controllers\JobController;
use App\Controllers\ProfileController;
use App\Controllers\ProjectController;
use App\Controllers\RefairController;
use App\Controllers\ReferralController;
use App\Controllers\UserController;
use App\Middleware\CsrfViewMiddleware;
use App\Middleware\OldInputMiddleware;
use App\Middleware\ValidationErrorsMiddleware;
use App\Router;
use DavidePastore\Slim\Validation\Validation;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Respect\Validation\Validator as v;
use RKA\Middleware\IpAddress;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Flash\Messages;
use Slim\Csrf\Guard;
use App\Middleware\PrizmMiddleware;
use Slim\Middleware\Session;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use SlimSession\Helper;

require __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (InvalidPathException $e) {
    // Log the error or handle it appropriately
    error_log('Could not load .env file: ' . $e->getMessage());
}

////SETTTINGS
$settings_mailer = [
    'host' => getenv('MAIL_HOST'),
    'username' => getenv('MAIL_USERNAME'),
    'password' => getenv('MAIL_PASSWORD'),
    'port'=> getenv('MAIL_PORT'),
    'secure' => getenv('MAIL_SECURE')
];

if (strpos(getenv('MAIL_HOST'), '@gmail.com') != -1) { // For gmail / tls to work.
    $settings_mailer['context'] = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];
}

// CLI Commands
$commands = [
    'ImportUsersCommand' => ImportUsersCommand::class,
    'ProfileEmailsCommand' => ProfileEmailsCommand::class,
    'ProfileSendCommand' => ProfileSendCommand::class,
    'FixCommand' => FixCommand::class
];

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'mailer' => $settings_mailer,
        'determineRouteBeforeAppMiddleware' => true
    ],
    'view' => [
        'template_path' => __DIR__ . getenv('TWIG_TEMPLATES'),
        'twig' => [
            'debug' => true,
            'auto_reload' => true,
        ],
    ],
    'logger' => [
        'name' => 'refairme',
        'path' => __DIR__ . getenv('LOG_PATH'),
    ],
    'displayErrorDetails' => true,
];


try{
    $app = AppFactory::create();
    $app->addRoutingMiddleware();

    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    // Register middleware for all routes
    // If you are implementing per-route checks you must not add this
    //$app->add(SlimCLIRunner::class);
    $app->add(new IpAddress(true));
    $app->add(new Session([
            'name' => 'prizm_session',
            'autorefresh' => true,
            'lifetime' => '1 hour'
        ])
    );

    $app->add(function($request, $response, $next) {
        $route = $request->getAttribute("route");

        $methods = [];

        if (!empty($route)) {
            $pattern = $route->getPattern();

            foreach ($this->router->getRoutes() as $route) {
                if ($pattern === $route->getPattern()) {
                    $methods = array_merge_recursive($methods, $route->getMethods());
                }
            }
            //Methods holds all of the HTTP Verbs that a particular route handles.
        } else {
            $methods[] = $request->getMethod();
        }

        $response = $next($request, $response);

        return $response;
    });

    require_once __DIR__ . '/database.php';
    require_once __DIR__ . '/scaffolds.php';

    $container['mailer'] = function($container) {
        return new Nette\Mail\SmtpMailer($container['settings']['mailer']);
    };

    $container['auth'] = function($container) {
        return new \App\Auth\Auth;
    };

    $container['flash'] = function($container) {
        return new Messages;
    };

    $container['session'] = function ($c) {
        return new Helper;
    };

    $container['view'] = function ($container) {
        $view = new Twig(__DIR__ . '/../'.env('TWIG_TEMPLATES'), [
            'cache' => false,
        ]);

        $view->addExtension(new TwigExtension(
            $container->router,
            $container->request->getUri()
        ));

        $view->getEnvironment()->addGlobal('auth',[
            'check' => $container->auth->check(),
            'user' => $container->auth->user()
        ]);

        $view->getEnvironment()->addGlobal('flash',$container->flash);

        return $view;
    };

    $container['validator'] = function ($container) {
        return new App\Validation\Validator;
    };

    $container['HomeController'] = function($container) {
        return new HomeController($container);
    };

    $container['AppointmentsController'] = function($container) {
        return new AppointmentsController($container);
    };

    $container['AdminController'] = function($container) {
        return new AdminController($container);
    };

    $container['ProfileController'] = function($container) {
        return new ProfileController($container);
    };

    $container['RefairController'] = function($container) {
        return new RefairController($container);
    };

    $container['AccountController'] = function($container) {
        return new CartController($container);
    };

    $container['CrawlerController'] = function($container) {
        return new CrawlerController($container);
    };

    $container['AuthController'] = function($container) {
        return new AuthController($container);
    };

    $container['PasswordController'] = function($container) {
        return new PasswordController($container);
    };

    $container['ConsoleController'] = function($container) {
        return new ConsoleController($container);
    };

    $container['JobController'] = function($container) {
        return new JobController($container);
    };

    $container['ReferralController'] = function($container) {
        return new ReferralController($container);
    };

    $container['ProjectController'] = function($container) {
        return new ProjectController($container);
    };

    $container['UserController'] = function($container) {
        return new UserController($container);
    };

    $container['csrf'] = function($container) {
        return new Guard;
    };

    Router::registerRoutes($app);

    $app->add(new ValidationErrorsMiddleware($container));
    $app->add(new OldInputMiddleware($container));
    $app->add(new CsrfViewMiddleware($container));

    //v::with('App\\Validation\\Rules\\');
    require __DIR__ . '/../app/Common.php';
}catch(Exception $e){
    print_r($e);
}