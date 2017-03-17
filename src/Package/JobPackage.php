<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Krak\Cargo;
use Krak\Job;

class JobPackage extends Lava\AbstractPackage
{
    public function with(Lava\App $app) {
        $app->commands([
            new Job\Console\ConsumeCommand(),
            new Job\Console\SchedulerCommand(),
            new Job\Console\WorkerCommand(),
        ]);
    }

    public function register(Cargo\Container $c) {
        $c[Job\Kernel::class] = new Job\Kernel($c);
        $c->alias(Job\Kernel::class, 'job');
        $c->wrap(Lava\Console\Application::class, function($console, $c) {
            $console->getHelperSet()->set(new Job\Console\JobHelper($c[Job\Kernel::class]));
            return $console;
        });
    }
}
