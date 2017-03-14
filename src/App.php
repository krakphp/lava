<?php

namespace Krak\Lava;

use ArrayObject;
use Krak\Cargo;
use Krak\Http;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Evenement\EventEmitterInterface;

class App extends Cargo\Container\ContainerDecorator implements EventEmitterInterface
{
    use EventEmitterTrait;
    use LoggerTrait;
    use PathsTrait;

    public function __construct($base_path = null, Cargo\Container $c = null) {
        parent::__construct($c ?: Cargo\container([], $auto_wire = true));

        $this['version'] = '0.1.0';
        $this['cli'] = php_sapi_name() === 'cli';
        $this['base_path'] = rtrim($base_path, DIRECTORY_SEPARATOR);

        $package = new LavaPackage();
        Cargo\register($this, $package);
        $package->with($this);
    }

    public function with($packages) {
        if (!is_array($packages)) {
            $packages = [$packages];
        }

        foreach ($packages as $package) {
            $this['packages']->append($package);

            if ($package instanceof Bootstrap) {
                $this->on(Events::BOOTSTRAP, [$package, 'bootstrap']);
            }
        }
    }

    public function __invoke(...$params) {
        $this->freeze();
        $mw = $this['stacks.http'];
        return $mw(...$params);
    }

    /** define the app routes */
    public function routes($def) {
        $this->wrap(Http\Route\RouteGroup::class, function($routes, $app) use ($def) {
            return $def($routes, $app) ?: $routes;
        });
        return $this;
    }

    public function commands($commands) {
        foreach ($commands as $command) {
            $this['commands']->append($command);
        }
    }

    public function response(...$args) {
        if (count($args) == 0) {
            return $this[Http\ResponseFactoryStore::class];
        }

        return $this[Http\ResponseFactory::class]->createResponse(...$args);
    }

    public function emitResponse(ResponseInterface $resp) {
        $this['Zend\Diactoros\Response\EmitterInterface']->emit($resp);
    }

    public function compose(array $mw) {
        $compose = $this['compose'];
        return $compose($mw);
    }

    public function abort(...$args) {
        return new Error\WrappedError(new Error(...$args), $this);
    }

    public function renderError(Error $err, ServerRequestInterface $req = null) {
        $render = $this->compose([$this['stacks.render_error']]);
        return $render($err, $req ?: $this[ServerRequestInterface::class])
            ->withStatus($err->status);
    }

    public function serve() {
        $this->bootstrap();
        $this->registerPackages();
        $this->freeze();

        $server = $this[Http\Server::class];
        $handler = $this->compose([$this['stacks.http']]);
        $server->serve($handler);

        $this->terminate();
    }

    public function bootstrap() {
        if ($this['frozen']) {
            return;
        }

        $this->emit(Events::BOOTSTRAP, [$this]);
    }

    public function freeze() {
        if ($this['frozen']) {
            return;
        }

        $this->emit(Events::FREEZE, [$this]);
        $this['frozen'] = true;
    }

    public function terminate() {
        $this->emit(Events::TERMINATE, [$this]);
    }

    public function registerPackages() {
        if ($this['frozen']) {
            return;
        }

        foreach ($this['packages'] as $package) {
            $match = false;
            if ($package instanceof Bootstrap) {
                $match = true;
            }
            if ($package instanceof Cargo\ServiceProvider) {
                Cargo\register($this, $package);
                $match = true;
            }
            if ($package instanceof Package) {
                $package->with($this);
                $match = true;
            }

            if (!$match) {
                $type = Util\typeToString($package);
                throw new InvalidArgumentException("Packages must be an instance of Krak\Lava\{Package, Bootstrap} or Krak\Cargo\ServiceProvider. $type was given");
            }
        }
    }
}
