<?php

namespace Krak\Lava\Package;

use Krak\Cargo;
use Krak\Lava;
use Krak\Validation;

class ValidationPackage extends Lava\AbstractPackage
{
    public function register(Cargo\Container $c) {
        $c[Validation\Kernel::class] = new Validation\Kernel($c);
        $c->alias(Validation\Kernel::class, 'validation');
    }
}