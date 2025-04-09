# Web Applications Framework based on Slim3 and Vue.js
[![License](https://img.shields.io/packagist/l/HavenShen/slim-born.svg?style=flat-square)](https://packagist.org/packages/HavenShen/slim-born)

### STAGE (reverse nginx proxy): http://ec2-18-185-73-100.eu-central-1.compute.amazonaws.com/
### STAGE Backend: http://ec2-18-185-73-100.eu-central-1.compute.amazonaws.com:31415
### STAGE Frontend: http://refairme-stage.s3-website.eu-central-1.amazonaws.com/
### PRODUCTION (reverse nginx proxy) : http://ec2-54-183-163-254.us-west-1.compute.amazonaws.com/
### PRODUCTION Backend: http://ec2-54-183-163-254.us-west-1.compute.amazonaws.com:31415/
### PRODUCITON Frontend: http://refairme.s3-website.eu-central-1.amazonaws.com/

backends available at :31415 port of each

> Slim Framework 3 skeleton application has an authentication MVC construction, as well as the database structure is held in the prizm.sql file.
> I know you love migrations but electrical engineers prefer it raw 

## Starting a pristine 
With a clean ArchLinux AMI on AWS ( or any host for that matter ) you need to run 

```
pacman -Syyu mariadb php-fpm nginx 
```
Then, install the db
```
mysql_install_db --user=mysql --basedir=/usr --datadir=/var/lib/mysql
```
After succeeding,  start the service
```
systemctl enable mariadb
systemctl start mariadb
```
Then, set the default passwords that need to go the dotenv file for the backend.
```
mysql_secure_installation
```
After succeeding here, turn to your nginx configuration. A default config for pristine has to be ceded upon you from an overlord.

However, a plain version for the lazy ones is here:

```
# Configuration File - Nginx Server Configs                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
# http://nginx.org/en/docs/dirindex.html                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
# Sets the worker threads to the number of CPU cores available in the system for best performance.                                                                                                                                                                           
# Should be > the number of CPU cores.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
# Maximum number of connections = worker_processes * worker_connections                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
# Default: 1                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               

worker_processes        auto;

# Maximum number of open files per worker process.                                                                                                                                                                                                                           
# Should be > worker_connections.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
# Default: no limit                                                                                                                                                                                                                                                          
                                  
# Default: no limit                                                                                                                                                                                                                                                          
                                                                                                                                                                                                                                                                              
worker_rlimit_nofile    8192;

events                  {
  # If you need more connections than this, you start optimizing your OS.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
  # That's probably the point at which you hire people who are smarter than you as this is *a lot* of requests.                                                                                                                                                                                                                                                                                                                                                                                                                                            
  # Should be < worker_rlimit_nofile.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
  # Default: 512                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
  worker_connections    8000;
}

# Log errors and warnings to this file                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
# This is only used when you don't override it on a server{} level                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
# Default: logs/error.log error                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
error_log               logs/error.log warn;

# The file storing the process ID of the main process                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
# Default: nginx.pid                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       

http                    {
     fastcgi_buffers    8 16k;
     fastcgi_buffer_size 32k;
  # Hide nginx version information.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
  # Default: on                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
  server_tokens         off;
  # Specify MIME types for files.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
  default_type          application/octet-stream;
  # Update charset_types to match updated mime.types.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
  # text/html is always included by charset module.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
  # Default: text/html text/xml text/plain text/vnd.wap.wml application/javascript application/rss+xml                                                                                                                                                                      
                                                                                                                                                                                                                                                                              
  charset_types
    text/css
    text/plain
    text/vnd.wap.wml
    application/javascript
    application/json
    application/rss+xml
    application/xml;

                                                                                                                                                                                                                                                                              
  log_format            main  '$remote_addr - $remote_user [$time_local] "$request" '
                    '$status $body_bytes_sent "$http_referer" '
                    '"$http_user_agent" "$http_x_forwarded_for"';

  # Log access to this file                                                                                                                                                                                                                                                   
  # Don't send out partial frames; this increases throughput                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
  # since TCP frames are filled up before being sent out.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
  # Default: off                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
  tcp_nopush            on;

  # Enable gzip compression.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
  # Default: off                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
  gzip                  on;

  # Compression level (1-9).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
  # 5 is a perfect compromise between size and CPU usage, offering about                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
  # 75% reduction for most ASCII files (almost identical to level 9).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
  # Default: 1                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
  gzip_comp_level       5;

  # Don't compress anything that's already small and unlikely to shrink much                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
  # if at all (the default is 20 bytes, which is bad as that usually leads to                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
  # larger files after gzipping).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
  # Default: 20                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
  gzip_min_length       256;
  # Compress data even for clients that are connecting to us via proxies,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
  # identified by the "Via" header (required for CloudFront).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
  # Default: off                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
  gzip_proxied          any;

 # Tell proxies to cache both the gzipped and regular version of a resource                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
  # whenever the client's Accept-Encoding capabilities header varies;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
  # Avoids the issue where a non-gzip capable client (which is extremely rare                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
  # today) would display gibberish if their proxy gave them the gzipped version.                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
  # Default: off                                                                                                                                                                                                                                                             
   
 gzip_vary             on;

  # Compress all output labeled with one of the following MIME-types.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
  # text/html is always compressed by gzip module.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
  # Default: text/html                                                                                                                                                                                                                                                       
                                                                                                                                                                                                                                                                              
  gzip_types
    application/atom+xml
    application/javascript
    application/json
    application/ld+json
    application/manifest+json
        application/rss+xml
    application/vnd.geo+json
    application/vnd.ms-fontobject
    application/x-font-ttf
    application/x-web-app-manifest+json
    application/xhtml+xml
    application/xml
    font/opentype
    image/bmp
    image/svg+xml
    image/x-icon
    text/cache-manifest
    text/css
    text/plain
    text/vcard
    text/vnd.rim.location.xloc
    text/vtt
    text/x-component
    text/x-cross-domain-policy;

  # This should be turned on if you are going to have pre-compressed copies (.gz) of                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
  # static files available. If not it should be left off as it will cause extra I/O                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
  # for the check. It is best if you enable this in a location{} block for                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
  # a specific directory, or on an individual server{} level.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
  # gzip_static on;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
  # Include files in the sites-enabled folder. server{} configuration files should be                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
  # placed in the sites-available folder, and then the configuration should be enabled                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
  # by creating a symlink to it in the sites-enabled folder.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
  # See doc/sites-enabled.md for more info.                                                                                                                                                                                                                                  
                                                                                                                                                                                                                                                                              
include                 sites-enabled/*;

}

```
Since the above is a general file, you need to add a frontend and backend now, and store them in /etc/nginx/sites-enabled. The frontend looks like this on stage. 

```
server {
    listen 80;
    #no SSL - this comes with certbot once you are on a domain
    server_name frontend.refair;
    root /usr/share/nginx/refairme/frontend/prizm/dist;
    index index.html;

    charset utf-8;

    location / {
        # Preflighted requests
        if ($request_method = 'OPTIONS') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
            #
            # Custom headers and headers various browsers *should* be OK with but aren't
            #
            add_header 'Access-Control-Allow-Headers' 'DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range';
            #
            # Tell client that this pre-flight info is valid for 20 days
            #
            add_header 'Access-Control-Max-Age' 1728000;
            add_header 'Content-Type' 'text/plain; charset=utf-8';
            add_header 'Content-Length' 0;
            return 204;
        }

        if ($request_method = 'POST') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
            add_header 'Access-Control-Allow-Headers' 'DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range';
            add_header 'Access-Control-Expose-Headers' 'DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range';
        }
        if ($request_method = 'GET') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
            add_header 'Access-Control-Allow-Headers' 'DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range';
            add_header 'Access-Control-Expose-Headers' 'DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range';
        } 
    }

    location ~ /\.ht {
        allow all;
    }
}
```

The backend, then is configured as follows:
```
server {
    listen DOTENV_PORT;
    root /usr/share/nginx/refairme/backend/;
    server_name backend.refair;

    index index.php;

    charset utf-8;

    location / {
        # Preflighted requests
        if ($request_method = 'OPTIONS') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
            #
            # Custom headers and headers various browsers *should* be OK with but aren't
            #
            add_header 'Access-Control-Allow-Headers' 'DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range,Access-Control-Allow-Origin,planck';
            #
            # Tell client that this pre-flight info is valid for 20 days
            #
            add_header 'Access-Control-Max-Age' 1728000;
            add_header 'Content-Type' 'text/plain; charset=utf-8';
            add_header 'Content-Length' 0;
            return 204;
        }

        if ($request_method = 'POST') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
            add_header 'Access-Control-Allow-Headers' 'DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range,Access-Control-Allow-Origin,planck';
            add_header 'Access-Control-Expose-Headers' 'DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range';
        }
        if ($request_method = 'GET') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
            add_header 'Access-Control-Allow-Headers' 'DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range,Access-Control-Allow-Origin,planck';
            add_header 'Access-Control-Expose-Headers' 'DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range';
        }

        try_files $uri $uri/ /index.php?/$request_uri;
        if (!-e $request_filename) {
            rewrite ^/(.*)$ /index.php?q=$1 last;
        }
    }
    access_log off;
    error_log /var/log/nginx/refair.error_log debug; rewrite_log on;

    sendfile off;

    client_max_body_size 100m;

    location /administration {                                                                                                                index  index.html;                                                                                                            
       proxy_pass http://localhost:8080;                                                                                                     proxy_http_version 1.1;                                                                                                        
      proxy_set_header Upgrade $http_upgrade;                                                                                               proxy_set_header Connection 'upgrade';                                                                                          
     proxy_set_header Host $host;                                                                                                          proxy_cache_bypass $http_upgrade;                                                                                                
}

    location ~* \.(jpg|jpeg|png|gif|ico|js|woff|woff2|ttf)$ {
        access_log off;
        expires max;
    }

    location ~ \.css {
        add_header  Content-Type    text/css;
    }

    location ~ \.js {
        add_header  Content-Type    application/x-javascript;
    }

    location ~ \.php$ {
        include       mime.types;
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        include fastcgi.conf;
        fastcgi_pass unix:/run/php-fpm/php-fpm.sock;
        fastcgi_index index.php;
    }

    location ~ /\.ht {
        allow all;
    }
}
```

_Now is a great moment to greate the /etc/nginx/errors directory_

You obviously need to change DOTENV_PORT to the port you want to use from your backend.

## Installation step 1 - build your database
Before anything, you will have to get your database structure up and running. The easiest way is to perform an import.

```
mysql -u <user> -p < prism.db.sql
```

More on this in https://stackoverflow.com/questions/105776/how-do-i-restore-a-dump-file-from-mysqldump

## Installation step 2 - build your .env

Before running your app, adding a new .env file is necessary. Below, a sample config. Empty values must be filled out.

```
DB_DRIVER=mysql
DB_HOST=localhost
DB_DATABASE=prism
DB_USERNAME=root
DB_PASSWORD= 
DB_PORT=3306
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_SECURE=true
MAIL_HOST=
MAIL_PORT=
TWIG_TEMPLATES='backend/views'
BASE_URL=' '
BACKEND_URL=' '
HASH_BASE=' '
AI_PATH='refairme'
OVERWATCH='emailoverwatch'
```

## Installation step 3 - enable PHP extensions
For starters, you should see the following error when running 'composer install' in the repository.
```
[]# composer install 
Loading composer repositories with package information
Installing dependencies (including require-dev) from lock file
Your requirements could not be resolved to an installable set of packages.

  Problem 1
    - Installation request for nette/mail v2.4.3 -> satisfiable by nette/mail[v2.4.3].
    - nette/mail v2.4.3 requires ext-iconv * -> the requested PHP extension iconv is missing from your system.

  To enable extensions, verify that they are enabled in your .ini files:
    - /etc/php/php.ini

```

Needless to say, if you did not install composer yet, or anything (emacs, vi - you name it), use pacman -S [package_name].
From now on, follow the errors. After updating php.ini (in /etc/php.ini), restart PHP using systemctl. 

Should you create your own extensions in any language but attatch them with a library such as PHPCPP, you obviously need to enable them as well.

## Install step 4 - Run Composer. Run NPM

### Caveat - before running NPM, create the Frontend .env. 

Navigate to the directory the app sits in and run `` composer install `` from the TLD.

Then, navigate to the prizm frontend and run `` npm install `` from that directory.


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
	$this->post('/auth/signin','AuthController:signIn');

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

