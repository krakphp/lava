<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Krak\Cargo;
use League\Plates;

class PlatesPackage extends Lava\AbstractPackage
{
    public function bootstrap(Lava\App $app) {
        $app['plates.views_path'] = $app->path('views');
    }

    public function with(Lava\App $app) {
        $app['stacks.http']->push(function($req, $next) {
            $app = $next->getApp();
            $plates = $app[Plates\Engine::class];
            $plates->addData([
                'request' => $req,
                'app' => $app,
            ]);
            return $next($req);
        });
        $app['stacks.marshal_response']->push(function($result, $req, $next) {
            $app = $next->getApp();
            $matches = Lava\Util\isTuple($result, "string", "array");
            if (!$matches) {
                return $next($result, $req);
            }

            list($template, $data) = $result;
            return $next->response()->html(
                200,
                [],
                $app[Plates\Engine::class]->render($template, $data)
            );
        });
        $app['stacks.render_error']->push(function($error, $req, $next) {
            $app = $next->getApp();
            $status = (string) $error->status;

            $paths = $app['plates.error_paths'];
            if (isset($paths[$status])) {
                $path = $paths[$status];
            } else if (isset($paths['error'])) {
                $path = $paths['error'];
            } else {
                return $next($error, $req);
            }

            return $next->response()->html(
                500,
                [],
                $app[Plates\Engine::class]->render($path, [
                    'error' => $error,
                ])
            );
        });
    }

    public function register(Cargo\Container $app) {
        if (!isset($app['plates.ext'])) {
            $app['plates.ext'] = 'php';
        }
        if (!isset($app['plates.error_paths'])) {
            $app['plates.error_paths'] = [];
        }
        $app[Plates\Engine::class] = function($app) {
            return new Plates\Engine($app['plates.views_path'], $app['plates.ext']);
        };

        Cargo\alias($app, Plates\Engine::class, 'plates');
    }
}
