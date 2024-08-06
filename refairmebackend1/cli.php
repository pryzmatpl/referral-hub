<?php
// application.php
require 'vendor/autoload.php';

use Phpmig\Console\Command;
use Symfony\Component\Console\Application;

$application = new Application();
$application->addCommands(array(
            new Command\InitCommand(),
            new Command\StatusCommand(),
            new Command\CheckCommand(),
            new Command\GenerateCommand(),
            new Command\UpCommand(),
            new Command\DownCommand(),
            new Command\MigrateCommand(),
            new Command\RollbackCommand(),
            new Command\RedoCommand()
        ));
$application->run();