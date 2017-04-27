<?php

namespace Krak\Lava;

use ArrayObject;
use Krak\Cargo;
use Krak\Http;
use Krak\Mw;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Krak\EventEmitter\EventEmitter;

class App extends Cargo\Container\ContainerDecorator implements EventEmitter, LoggerInterface
{
    use Concerns\EventEmitter;
    use Concerns\Logger;
    use Concerns\Paths;
    use Concerns\Stacks;
    use Concerns\Http;
    use Concerns\Console;

    public static $instance;

    public function __construct($base_path = null, Cargo\Container $c = null) {
        parent::__construct($c ?: Cargo\container([], $auto_wire = true));

        if ($base_path) {
            $this->addPath('base', $base_path);
        }
        $this->with(new LavaPackage());
    }

    public function with($packages) {
        if (!is_array($packages)) {
            $packages = [$packages];
        }

        foreach ($packages as $package) {
            if ($package instanceof Cargo\ServiceProvider) {
                Cargo\register($this, $package);
            }
            if ($package instanceof Package) {
                $package->with($this);
            }
            if (is_callable($package)) {
                $package($this);
            }
            if ($package instanceof Bootstrap) {
                $this->bootstrap([$package, 'bootstrap']);
            }
        }
    }

    public function compose(array $mw) {
        $compose = $this['compose'];
        return $compose($mw);
    }

    public function bootstrap(callable $bootstrap = null) {
        if ($bootstrap) {
            return $this['bootstrappers']->append($bootstrap);
        }

        if ($this['bootstrapped']) {
            return;
        }

        foreach ($this['bootstrappers'] as $bootstrap) {
            $bootstrap($this);
        }

        $this['bootstrapped'] = true;
    }
}
