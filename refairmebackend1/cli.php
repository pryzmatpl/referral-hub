<?php
require 'vendor/autoload.php';

use Phpmig\Console\Command;
use Symfony\Component\Console\Application;

$application = new Application();
$configFile = __DIR__ . '/config/phpmig.php';

print_r("                               \n");
print_r(" ++++++++++++++++++++++++++++++\n");
print_r(" * Welcome to the AiMatch CLI *\n");
print_r(" ++++++++++++++++++++++++++++++\n");

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