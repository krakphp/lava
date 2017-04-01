<?php

namespace Krak\Lava\Concerns;

use Krak\Lava;

trait Console {
    public function commands($commands) {
        foreach ($commands as $command) {
            $this['commands']->append($command);
        }
    }

    public function addCommand($name, \Closure $command) {
        $command = new Lava\Console\ClosureCommand($name, $command);
        $this->commands([$command]);
        return new Lava\Console\CommandDefinitionWrapper($command);
    }

    public function runConsole() {
        exit($this['console']->run());
    }
}
