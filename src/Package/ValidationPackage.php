<?php

namespace Krak\Lava\Package;

use Krak\Cargo;
use Krak\Lava;
use Krak\Lava\Package\Validation\ConvertViolationExceptionRenderError;
use Krak\Validation;

class ValidationPackage implements Cargo\ServiceProvider, Lava\Package
{
    public function with(Lava\App $app) {
        $app->renderErrorStack()->push(ConvertViolationExceptionRenderError::class, 1, 'convert_violations');
    }

    public function register(Cargo\Container $c) {
        $c[Validation\Kernel::class] = new Validation\Kernel($c);
        $c->alias(Validation\Kernel::class, 'validation');
    }
}
