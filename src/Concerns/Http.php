<?php

namespace Krak\Lava\Concerns;

use Krak\Http\Route;
use Krak\Http\ResponseFactoryStore;
use Krak\Http\ResponseFactory;
use Krak\Http\Server;
use Krak\Http\Concerns\Response;
use Krak\Lava;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function Krak\Mw\splitArgs;

trait Http {
    use Response;

    // invoke as middleware by starting this app
    public function __invoke(...$params) {
        $this->bootstrap();
        $this->freeze();
        $handler = $this->compose([$this->httpStack()]);
        list($params) = splitArgs($params);
        return $handler(...$params);
    }

    /** define the app routes */
    public function routes($def) {
        $this->wrap(Route\RouteGroup::class, function($routes, $app) use ($def) {
            return $def($routes, $app) ?: $routes;
        });
        return $this;
    }

    public function emitResponse(ResponseInterface $resp) {
        $this['Zend\Diactoros\Response\EmitterInterface']->emit($resp);
    }

    public function abort(...$args) {
        return new Lava\Error\WrappedError(new Lava\Error(...$args), $this);
    }

    public function renderError(Lava\Error $err, ServerRequestInterface $req = null) {
        $render = $this->compose([$this->renderErrorStack()]);
        return $render($err, $req ?: $this[ServerRequestInterface::class])
            ->withStatus($err->status);
    }

    public function handleRequest(ServerRequestInterface $req = null) {
        $this->bootstrap();
        $this->freeze();
        $handler = $this->compose([$this->httpStack()]);
        return $handler($req ?: $this[ServerRequestInterface::class]);
    }

    public function serve() {
        $this->bootstrap();
        $this->freeze();

        $server = $this[Server::class];
        $handler = $this->compose([$this->httpStack()]);
        $server->serve($handler);

        $this->terminate();
    }

    public function freeze() {
        if ($this['frozen']) {
            return;
        }

        $this->emit(Lava\Events::FREEZE, $this);
        $this['frozen'] = true;
    }

    public function terminate() {
        $this->emit(Lava\Events::TERMINATE, $this);
    }
}
