<?php

namespace Krak\Lava\Concerns;

trait Console {
    public function commands($commands) {
        foreach ($commands as $command) {
            $this['commands']->append($command);
        }
    }
    public function runConsole() {
        exit($this['console']->run());
    }
}
