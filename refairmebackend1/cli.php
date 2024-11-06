<?php
require './vendor/autoload.php';
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Console\Application;

$dotenv = new Dotenv();
$dotenv->load('./.env');

$application = new Application();
$appName = env('APP_NAME');
$application->setName($appName);
print_r("++++++ CLI +++++\n");


$application->addCommands([]);
$application->run();