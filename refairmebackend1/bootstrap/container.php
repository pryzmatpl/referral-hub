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
use App\Services\EmailService;
use App\Services\LinkedInService;
use App\Services\UserService;
use DI\ContainerBuilder;
use GuzzleHttp\Client;
use Nette\Mail\Mailer;
use Slim\Csrf\Guard;
use Slim\Psr7\Environment;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;
use Slim\Flash\Messages;
use Nette\Mail\SmtpMailer;
use App\Auth\Auth;
use App\Validation\Validator;


use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use SlimSession\Helper;

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
            return new Nette\Mail\SmtpMailer(
                host: $_ENV['MAIL_HOST'],
                username: $_ENV['MAIL_USERNAME'],
                password:  $_ENV['MAIL_PASSWORD'],
                port: $_ENV['MAIL_PORT'],
                encryption: $_ENV['MAIL_ENCRYPTION'],
            );
        },
        'db' => function ($c) {
            $log = $c->get('logger');
            $log->debug('DB_HOST: ' . $_ENV['DB_HOST']);
            $log->debug('DB_DATABASE: ' . $_ENV['DB_DATABASE']);
            return new PDO(
                "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'],
                $_ENV['DB_USERNAME'],
                $_ENV['DB_PASSWORD'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        },
        'logger' => function () {
            $log = new Logger('main');
            $log->pushHandler(new StreamHandler(__DIR__ . '/../storage/logs/app.log', Level::Debug));
            return $log;
        },
        'csrf' => function($c) {
            return new Guard($c->get(ResponseFactory::class));
        },
        'session' => function ($c) {
            return new Helper();
        },
        Client::class => function($c) {
            return new Client();
        },
        Environment::class => function($c) {
            return new Environment();
        },
        'email.template.path' => 'resources/emails',
        EmailService::class => function ($c) {
            return new EmailService(
                $c->get('mailer'),
                $c->get(Environment::class),
                $c->get('logger'),
                $c->get('email.template.path'));
        },
        LinkedInService::class => function ($c) {
            return new LinkedInService(
                $c->get(Client::class),
                $c->get('linkedin.client_id'),
                $c->get('linkedin.client_secret')
            );
        },
        UserService::class => function ($c) {
            return new UserService(
                $c->get(EmailService::class)
            );
        },
        AuthController::class => function ($c) {
            return new AuthController(
                $c->get('auth'),
                $c->get('validator'),
                $c->get('mailer'),
                $c->get('logger'),
                $c->get(UserService::class),
                $c->get(LinkedInService::class),
                $c->get(Client::class),
            );
        }
    ]);
};
