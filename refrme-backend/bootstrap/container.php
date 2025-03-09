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
use App\Controllers\JobController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfViewMiddleware;
use App\Repositories\JobRepository;
use App\Services\Auth\AuthService;
use App\Services\EmailService;
use App\Services\JobClassificationService;
use App\Services\JobService;
use App\Services\LinkedInService;
use App\Services\UserService;
use DI\ContainerBuilder;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Csrf\Guard;
use Slim\Psr7\Factory\ResponseFactory;
use SlimSession\Helper;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        ResponseFactoryInterface::class => function (ContainerInterface $container) {
            return $container->get(ResponseFactory::class);
        },
        'auth' => function ($c) {
            return new AuthService(
                $c->get('db'),
                $c->get('session'),
                $c->get('logger')
            );
        },
        AuthMiddleware::class => function (ContainerInterface $c) {
            return new AuthMiddleware(
                $c->get('auth'),
                $c->get(ResponseFactoryInterface::class)
            );
        },
        CsrfViewMiddleware::class => function (ContainerInterface $c) {
            return new CsrfViewMiddleware(
                $c,
                $c->get(ResponseFactoryInterface::class)
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
        'email.template.path' => '/var/www/html/resources/emails',
        'twigenv'=> function($c) {
            $loader = new FilesystemLoader($c->get('email.template.path'));
            return new Environment($loader);
        },
        EmailService::class => function ($c) {
            return new EmailService(
                $c->get('mailer'),
                $c->get('twigenv'),
                $c->get('logger'),
                $c->get('email.template.path'));
        },
        LinkedInService::class => function ($c) {
            return new LinkedInService(
                $c->get('logger'),
                $c->get(Client::class),
                $_ENV['LINKEDIN_CLIENT_ID'],
                $_ENV['LINKEDIN_CLIENT_SECRET']
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
        },
        JobRepository::class => function($c) {
            return new JobRepository(
                $c->get('logger'),
                $c->get(JobClassificationService::class)
            );
        },
        JobClassificationService::class => function($c) {
            return new JobClassificationService(
                $c->get('logger'),
                'python',
                '../models/match'
            );
        },
        JobController::class => function ($c) {
            return new JobController(
                $c->get(JobService::class),
                $c->get(JobRepository::class),
                $c->get(JobClassificationService::class)
            );
        }
    ]);
};
