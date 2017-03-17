<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Krak\Cargo;

use function iter\mapWithKeys, iter\toArray;

class RESTPackage extends Lava\AbstractPackage
{
    public function with(Lava\App $app) {
        // default to json marshaling
        $app['stacks.marshal_response']->push(Lava\MarshalResponse\jsonMarshalResponse());
        $app['stacks.render_error']->push(REST\restRenderError());
    }
}
