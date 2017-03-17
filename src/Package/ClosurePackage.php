<?php

namespace Krak\Lava\Package;

use Krak\Lava;

class ClosurePackage implements Lava\Package
{
    private $closure;

    public function __construct(\Closure $closure) {
        $this->closure = $closure;
    }

    public function with(Lava\App $app) {
        $closure = $this->closure;
        return $closure($app);
    }
}
