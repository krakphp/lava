<?php

namespace Krak\Lava;

use ArrayObject;
use Krak\Cargo;
use Krak\Http;
use Krak\Mw;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Krak\EventEmitter\EventEmitter;

class App extends Cargo\Container\ContainerDecorator implements EventEmitter, LoggerInterface
{
    use EventEmitterTrait;
    use LoggerTrait;
    use PathsTrait;

    public function __construct($base_path = null, Cargo\Container $c = null) {
        parent::__construct($c ?: Cargo\container([], $auto_wire = true));

        $this['base_path'] = $base_path ? rtrim($base_path, DIRECTORY_SEPARATOR) : null;
        $this->protect('compose', Mw\composer(
            new Middleware\LavaContext($this),
            Middleware\LavaLink::class
        ));
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
                $this->on(Events::BOOTSTRAP, [$package, 'bootstrap']);
            }
        }
    }

    // invoke as middleware by starting this app
    public function __invoke(...$params) {
        $this->bootstrap();
        $this->freeze();
        $handler = $this->compose([$this['stacks.http']]);
        list($params) = Mw\splitArgs($params);
        return $handler(...$params);
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
        $this->freeze();

        $server = $this[Http\Server::class];
        $handler = $this->compose([$this['stacks.http']]);
        $server->serve($handler);

        $this->terminate();
    }

    public function bootstrap(callable $bootstrap = null) {
        if ($bootstrap) {
            return $this->on(Events::BOOTSTRAP, $bootstrap);
        }
        if ($this['frozen']) {
            return;
        }

        $this->emit(Events::BOOTSTRAP, $this);
    }

    public function freeze() {
        if ($this['frozen']) {
            return;
        }

        $this->emit(Events::FREEZE, $this);
        $this['frozen'] = true;
    }

    public function terminate() {
        $this->emit(Events::TERMINATE, $this);
    }
}
