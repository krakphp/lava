<?php

namespace Krak\Lava\Error;

use Krak\Lava;
use Psr\Http\Message\ServerRequestInterface;

class WrappedError
{
    private $error;
    private $app;

    public function __construct(Lava\Error $error, Lava\App $app) {
        $this->error = $error;
        $this->app = $app;
    }

    public function render(ServerRequestInterface $req = null) {
        return $this->app->renderError($this->error, $req);
    }

    public function __call($method, $args) {
        if (strpos($method, 'get') === 0) {
            return $this->error->{$method}(...$args);
        } else if (strpos($method, 'with') === 0) {
            $this->error = $this->error->{$method}(...$args);
            return $this;
        }

        throw new \BadMethodCallException('Method '.$method.' does not exist');
    }
}
