<?php

namespace Krak\Lava\Middleware;

use Krak\Mw;
use Krak\Lava;

class LavaContext extends Mw\Context\ContainerContext
{
    private $app;

    public function __construct(Lava\App $app) {
        $this->app = $app;
        $c = $app->toInterop();
        parent::__construct(
            $c,
            Mw\containerAwareInvoke($c, Mw\methodInvoke('handle'))
        );
    }

    public function getApp() {
        return $this->app;
    }
}
