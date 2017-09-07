<?php

use Krak\Lava;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

describe(Lava\Console::class, function() {
    describe('Command', function() {
        it('can create a consoleLogger', function(InputInterface $input, OutputInterface $output) {
            $cmd = new Lava\Console\ClosureCommand('name', function() {
                assert($this->consoleLogger() instanceof ConsoleLogger);
                return 1;
            });
            $cmd->setLava(new Lava\App());
            assert($cmd->run($input, $output) == 1);
        });
    });
});
