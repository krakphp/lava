<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Krak\Cargo;

use function iter\mapWithKeys, iter\toArray;

class RESTPackage implements Lava\Package
{
    public function with(Lava\App $app) {
        // default to json marshaling
        $app->marshalResponseStack()->toTop('json');
        $app->renderErrorStack()->push(REST\restRenderError(), 0, 'rest');
    }
}
