<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Krak\Cargo;
use League;

class PlatesPackage extends Lava\AbstractPackage
{
    public function with(Lava\App $app) {
        $app['plates.views_path'] = $app->viewsPath();
        $app->httpStack()->push(Plates\injectRequestIntoPlates());
        $app->marshalResponseStack()->push(Plates\platesMarshalResponse(), 0, 'plates');
        $app->renderErrorStack()->push(Plates\platesRenderError(), 0, 'plates');
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
