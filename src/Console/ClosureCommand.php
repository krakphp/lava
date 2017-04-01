<?php

namespace Krak\Lava\Console;

use Krak\Lava;
use Krak\Cargo;
use Krak\AutoArgs;
use Symfony\Component\Console;
use Closure;

class ClosureCommand extends Command
{
    protected $command;

    public function __construct($name, Closure $command) {
        parent::__construct($name);
        $this->command = $command;
    }

    protected function configure() {

    }

    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output) {
        $this->input = $input;
        $this->output = $output;

        $aa = $this->lava[AutoArgs\AutoArgs::class];
        $command = $this->command->bindTo($this);
        return $aa->invoke($command, [
            'objects' => [$this->lava, $this->input, $this->output],
            'container' => Cargo\toInterop($this->lava),
        ]);
    }
}
