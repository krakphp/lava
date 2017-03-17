<?php

namespace Krak\Lava\Package\Plates;

use Krak\Lava;
use League\Plates;

function injectRequestIntoPlates() {
    return function($req, $next) {
        $app = $next->getApp();
        $plates = $app[Plates\Engine::class];
        $plates->addData([
            'request' => $req,
            'app' => $app,
        ]);
        return $next($req);
    };
}

function platesMarshalResponse() {
    return function($result, $req, $next) {
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
    };
}

function platesRenderError() {
    return function($error, $req, $next) {
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
    };
}
