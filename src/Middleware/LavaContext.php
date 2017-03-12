<?php

namespace Krak\Lava\Middleware;

use Krak\Mw;
use Krak\Lava;

class LavaContext extends Mw\Context\ContainerContext
{
    private $app;

    public function __construct(Lava\App $app) {
        $this->app = $app;
        parent::__construct($app->toInterop());
    }

    public function getApp() {
        return $this->app;
    }
}
