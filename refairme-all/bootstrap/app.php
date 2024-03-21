<?php
use Respect\Validation\Validator as v;
use Aurmil\Slim\CsrfTokenToView;
use Aurmil\Slim\CsrfTokenToHeaders;
use Slim\Http\Request;
use Slim\Csrf\Guard;
use Slim\Http\Response;
use App\Middleware\PrizmMiddleware;

session_start();

require __DIR__ . '/../vendor/autoload.php';

try {
  $dotenv = (new \Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
  //
  }

////SETTTINGS
$app = new \Slim\App([
		      'settings' => [
				     'displayErrorDetails' => true,
				     'mailer' => [
						  'host' => getenv('MAIL_HOST'),
						  'username' => getenv('MAIL_USERNAME'),
						  'password' => getenv('MAIL_PASSWORD'),
						  'port'=> getenv('MAIL_PORT'),
						  'secure' => getenv('MAIL_SECURE')
						  ],
				     'determineRouteBeforeAppMiddleware' => true
				     ],
		      'view' => [
				 'template_path' => __DIR__ . getenv('TWIG_TEMPLATES'),
				 'twig' => [
					    'debug' => true,
					    'auto_reload' => true,
					    ],
				 ],

		      // monolog settings
		      'logger' => [
				   'name' => 'refairme',
				   'path' => __DIR__ . getenv('LOG_PATH'),
				   ],
		      //error
		      'displayErrorDetails' => true

		      ]);

$container = $app->getContainer();

$_SESSION['app']=$container;

//Create the validators
$usernameValidator = v::alnum()->noWhitespace()->length(1, 15);
$ageValidator = v::numeric()->positive()->between(1, 20);
$validators = array(
		    'username' => $usernameValidator,
		    );

// Register middleware for all routes
// If you are implementing per-route checks you must not add this
$app->add(new \DavidePastore\Slim\Validation\Validation($validators));

$app->add(new \RKA\Middleware\IpAddress(true));

$app->add(new \Slim\Middleware\Session([
					'name' => 'prizm_session',
					'autorefresh' => true,
					'lifetime' => '1 hour'
					])
	  );

$app->add(new \App\Middleware\PrizmMiddleware($container));

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
    
    return $response->withHeader("Access-Control-Allow-Methods", implode(",", $methods))
                    ->withHeader("Access-Control-Allow-Origin", '*');
  });

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/oauth2.php';
require_once __DIR__ . '/scaffolds.php';

$container['mailer'] = function($container) {
  return new Nette\Mail\SmtpMailer($container['settings']['mailer']);
};

$container['auth'] = function($container) {
  return new \App\Auth\Auth;
};

$container['oauth2'] = function($container) {
  return new Middleware\Authorization($server, $app->getContainer());
};

$container['flash'] = function($container) {
  return new \Slim\Flash\Messages;
};

// Register globally to app
$container['session'] = function ($c) {
  return new \SlimSession\Helper;
};

$container['view'] = function ($container) {
  $view = new \Slim\Views\Twig(__DIR__ . '/../'.env('TWIG_TEMPLATES'), [
								  'cache' => false,
								  ]);

  $view->addExtension(new \Slim\Views\TwigExtension(
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
  return new \App\Controllers\HomeController($container);
};

$container['AdminController'] = function($container) {
  return new \App\Controllers\AdminController($container);
};

$container['ProfileController'] = function($container) {
  return new \App\Controllers\ProfileController($container);
};

$container['RefairController'] = function($container) {
  return new \App\Controllers\RefairController($container);
};

$container['AccountController'] = function($container) {
  return new \App\Controllers\CartController($container);
};

$container['CrawlerController'] = function($container) {
  return new \App\Controllers\CrawlerController($container);
};

$container['AuthController'] = function($container) {
  return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function($container) {
  return new \App\Controllers\Auth\PasswordController($container);
};

$container['csrf'] = function($container) {
  return new \Slim\Csrf\Guard;
};

$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));

v::with('App\\Validation\\Rules\\');

require __DIR__ . '/../app/routes.php';

