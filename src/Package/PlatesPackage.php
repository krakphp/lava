<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Krak\Cargo;
use League;

class PlatesPackage extends Lava\AbstractPackage
{
    public function with(Lava\App $app) {
        $app['plates.views_path'] = $app->path('views');
        $app['stacks.http']->push(Plates\injectRequestIntoPlates());
        $app['stacks.marshal_response']->push(Plates\platesMarshalResponse());
        $app['stacks.render_error']->push(Plates\platesRenderError());
    }

    public function register(Cargo\Container $app) {
        $app['plates.ext'] = 'php';
        $app['plates.error_paths'] = [];
        $app[League\Plates\Engine::class] = function($app) {
            return new League\Plates\Engine($app['plates.views_path'], $app['plates.ext']);
        };
        Cargo\alias($app, League\Plates\Engine::class, 'plates');
    }
}
