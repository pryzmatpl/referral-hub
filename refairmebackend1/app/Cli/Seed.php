<?php

namespace App\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class Seed extends Command
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            // 06 defining the command name
            ->setName('seed')
            // 07 defining the description of the command
            ->setDescription('Run phinx seeding')
            // 08 defining the help (shown with -h option)
            ->setHelp('Runs phinx seeding using the cli');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $resp = system('./vendor/bin/phinx seed');
        $output->write($resp);
        return Command::SUCCESS;
    }

}