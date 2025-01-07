<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 07.01.2025, 21:07
 * container.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */

use App\Controllers\Auth\AuthController;
use DI\ContainerBuilder;
use Slim\Csrf\Guard;
use Slim\Views\Twig;
use Slim\Flash\Messages;
use Nette\Mail\SmtpMailer;
use App\Auth\Auth;
use App\Validation\Validator;


use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'auth' => function ($c) {
            return new App\Auth\Auth(
                $c->get('db'),
                $c->get('session'),
                $c->get('logger')
            );
        },
        'flash' => function () {
            return new Slim\Flash\Messages();
        },
        'validator' => function () {
            return new App\Validation\Validator();
        },
        'mailer' => function () {
            return new Nette\Mail\SmtpMailer([
                "host" => getenv('MAIL_HOST'),
                "username" => getenv('MAIL_USERNAME'),
                "password" => getenv('MAIL_PASSWORD'),
                "secure" => getenv('MAIL_ENCRYPTION', 'tls'),
                "port" => getenv('MAIL_PORT', 587),
            ]);
        },
        'db' => function ($c) {
            return new PDO(
                "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_DATABASE'),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD'),
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        },
        'logger' => function ($c) {
            $log = new Logger('main');
            $log->pushHandler(new StreamHandler(__DIR__ . '/../storage/logs/app.log', Level::Debug));
            return $log;
        },
        'csrf' => function($c) {
            return new Guard;
        },
        AuthController::class => function ($c) {
            return new AuthController(
                $c->get('auth'),
                $c->get('validator'),
                $c->get('mailer'),
                $c->get('logger'),
            );
        }
    ]);
};
