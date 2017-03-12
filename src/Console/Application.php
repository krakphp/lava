<?php

namespace Krak\Lava\Console;

use Krak\Lava;
use Symfony\Component\Console;

class Application extends Console\Application
{
    private $lava;
    private $commands_registered = false;

    public function __construct(Lava\App $lava) {
        parent::__construct('Lava', $lava['version']);

        $this->lava = $lava;
        $this->lava->bootstrap();

        foreach ($this->lava['commands'] as $command) {
            if (is_string($command)) {
                $command = $this->lava[$command];
            }

            $this->add($command);
        }
    }

    public function getLava() {
        return $this->lava;
    }

    public function add(Console\Command\Command $command) {
        if ($command instanceof Command) {
            $command->setLava($this->lava);
        }

        return parent::add($command);
    }
}
