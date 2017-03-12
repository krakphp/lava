<?php

namespace Krak\Lava\Console;

use Krak\Lava;
use Krak\Cargo;
use Krak\AutoArgs;
use Symfony\Component\Console;

class Command extends Console\Command\Command
{
    protected $lava;
    protected $input;
    protected $output;

    public function setLava(Lava\App $lava) {
        $this->lava = $lava;
    }

    public function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output) {
        $this->input = $input;
        $this->output = $output;

        if (!method_exists($this, 'handle')) {
            throw new \LogicException('Command does not contain the handle method');
        }

        $aa = $this->lava[AutoArgs\AutoArgs::class];
        return $aa->invoke([$this, 'handle'], [
            'objects' => [$this->lava, $this->input, $this->output],
            'container' => Cargo\toInterop($this->lava),
        ]);
    }

    protected function input() {
        return $this->input;
    }

    protected function output() {
        return $this->output;
    }
}
