<?php

namespace App\Cli;

use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class CleanDB extends Command
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            // 06 defining the command name
            ->setName('cleandb')
            // 07 defining the description of the command
            ->setDescription('Clean the main db')
            // 08 defining the help (shown with -h option)
            ->setHelp('Runs an SQL query to cleanup a db');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $host = env('DB_HOST');
        $username = env('DB_USERNAME');
        $database = env('DB_DATABASE');
        $password = env('DB_PASSWORD');
        $pdo = new PDO("mysql:host=".$host.";dbname=".$database, $username, $password);

        $status = $pdo->exec("DROP DATABASE ".$database);
        $output->write("Removal of db:" . $status . "\n");

        $status = $pdo->exec("CREATE DATABASE ".$database);
        $output->write("Create db:" . $status . "\n");

        return $status==1 ? Command::SUCCESS : Command::FAILURE;
    }

}