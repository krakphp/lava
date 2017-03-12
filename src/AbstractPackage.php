<?php

namespace Krak\Lava;

use Krak\Cargo;

abstract class AbstractPackage implements Bootstrap, Package, Cargo\ServiceProvider
{
    public function bootstrap(App $app) {}
    public function with(App $app) {}
    public function register(Cargo\Container $app) {}
}
