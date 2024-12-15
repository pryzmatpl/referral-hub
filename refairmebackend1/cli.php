<?php
require './vendor/autoload.php';

use App\Cli\Migrate;
use App\Cli\Seed;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Console\Application;

/**
 * Load .env
 */
$dotenv = new Dotenv();
$dotenv->load('./.env');

/**
 * Start app
 */
$application = new Application();
$appName = env('APP_NAME');
$application->setName($appName);
print_r("++++++ CLI +++++\n");

/**
 * Set commands
 */
$application->addCommands([
    new Migrate(),
    new Seed()
]);

$application->run();