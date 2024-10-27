# Web Applications Framework based on Slim3 and Vue.js
[![License](https://img.shields.io/packagist/l/HavenShen/slim-born.svg?style=flat-square)](https://packagist.org/packages/HavenShen/slim-born)

> Slim Framework 3 skeleton application has an authentication MVC construction, as well as the database structure is held in the prizm.sql file.
> I know you love migrations but electrical engineers prefer it raw 


## Installation step 1 - build your database

Before anything, you will have to get your database structure up and running. The easiest way is to perform a mysqldump.

```
mysql -u <user> -p < prizm.sql
```

More on this in https://stackoverflow.com/questions/105776/how-do-i-restore-a-dump-file-from-mysqldump

## Installation step 2 - build your .env

Before running your app, adding a new .env file is necessary. Below, a sample config

```
DB_DRIVER=mysql
DB_HOST=localhost
DB_DATABASE=prizm
DB_USERNAME=root
DB_PASSWORD=yourpassword
DB_PORT=3306
BASE_URL=yourbaseurl.yourdomain.yourtld
```

## Installation step 3 - change ./frontend/baseurl.js to your BASE_URL 
Naturally, you can change the baseurl for js using dotenv or another nice library. I found that this works best for me.   

## Router
This project uses a Slim framework router. You can (if you want and need) implement a Vue router if your application grows.
The Slim router is used as a request dispatcher and that is it's primary goal. High efficiency code is handed to the endpoints by means of C++ plugins in PHP.

A small route example is available below

Reference - [Slim Router](http://www.slimframework.com/docs/objects/router.html). 

```php
<?php

$app->get('/','HomeController:index')->setName('home');


$app->group('',function () {

	$this->get('/auth/signup','AuthController:getSignUp')->setName('auth.signup');
	$this->post('/auth/signup','AuthController:postSignUp');

	$this->get('/auth/signin','AuthController:getSignIn')->setName('auth.signin');
	$this->post('/auth/signin','AuthController:postSignIn');

})->add(new GuestMiddleware($container));
```

## Controller

We Use Slim Framework Twig View for booting only.
Reference - [Twig-View](https://github.com/slimphp/Twig-View)

The point is to load the UX once and then Vue takes over - we need to serve some html first though.

Below an example Home Controller. Please refer to the Docs of Slim3 for further reading.
```php
<?php

namespace App\Controllers;

class HomeController extends Controller
{
	public function index($request,$response)
	{
		return $this->view->render($response,'home.twig');
	}
}
```

## Model

We Use Laravel PHP Framework Eloquent element for database connections.
Reference - [illuminate/database](https://github.com/illuminate/database)
```php
<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{

	protected $table = 'users';
	protected $fillable = [
		'email',
		'name',
		'password',
	];
	public function setPassword($password)
	{
		$this->update([
			'password' => password_hash($password,PASSWORD_DEFAULT)
		]);
	}
}
```

## Middleware
Auth middleware is added:
```php
<?php

namespace App\Middleware;

class AuthMiddleware extends Middleware
{

	public function __invoke($request,$response,$next)
	{
		if(!$this->container->auth->check()) {
			$this->container->flash->addMessage('error','Please sign in before doing that');
			return $response->withRedirect($this->container->router->pathFor('auth.signin'));
		}
		$response = $next($request,$response);
		return $response;
	}
}
```

## Validation

Use the most awesome validation engine ever created for PHP.
Reference - [Respect/Validation](https://github.com/Respect/Validation)
```php
<?php

namespace App\Controllers\Auth;
use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{
	public function postSignUp($request,$response)
	{
		$validation = $this->validator->validate($request,[
			'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
			'name' => v::noWhitespace()->notEmpty()->alpha(),
			'password' => v::noWhitespace()->notEmpty(),
		]);
		if ($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}
		//	todo someting
	}
}
```

## More basic functions

reference slim official documents - [Slim Framework](http://www.slimframework.com/docs/)

## Use Packages

* [illuminate/database](https://github.com/illuminate/database) - It also serves as the database layer of the Laravel PHP framework.
* [Respect/Validation](https://github.com/Respect/Validation) - The most awesome validation engine ever created for PHP.
* [slimphp/Slim](https://github.com/slimphp/Slim) - Slim Framework created.
* [slimphp/Slim-Csrf](https://github.com/slimphp/Slim-Csrf) - Slim Framework created.
* [slimphp/Twig-View](https://github.com/slimphp/Twig-View) - Slim Framework created.
* [slimphp/Slim-Flash](https://github.com/slimphp/Slim-Flash) - Slim Framework created.

## Look Feel

![slimborn look feel](slimborn.png)

## Directory Structure

```shell
|-- prizm
	|-- app
		|-- Auth
		|-- Controllers
		|-- Middleware
		|-- Models
		|-- Validation
		|-- routes.php
	|-- bootstrap
		|-- app.php
		|-- migrations
		|-- database.php
		|-- oauth2.php
		|-- scaffolds.php
	|-- frontend
		|-- components
		|-- jquery.json2html
		|-- boot.js
		|-- baseurl.js
		|-- Gruntfile.js
		|-- json2html.js
		|-- package.json
		|-- prizm-vuex.js
		|-- vue.config.js
		|-- vueapp.js
	|-- public
		|-- assets
				|-- css
				|-- images
		|-- freelancer
		|-- admin
		|-- bootprizm.js
		|-- index.php
		|-- phpinfo.php
	|-- resources
		|-- pythonapis
		|-- sql
		|-- views
				|-- auth
				|-- templates
								|-- partials
								|-- convert.twig
								|-- landing.twig
								|-- slavingway.twig
				|-- app-boot.twig
	|-- tests
		|-- unit
	|-- cli.php
	|-- composer.json
	|-- phpinfo.php
	|-- phpunit.xml
	|-- README.md
	|-- LICENSE
	....
```

## Testing

``` bash
$ phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
=======
# pristine prizm 
Pristine PRIZM with some preconf

