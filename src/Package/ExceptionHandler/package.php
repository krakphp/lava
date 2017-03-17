<?php

namespace Krak\Lava\Package\ExceptionHandler;

use Krak\Lava;

function exceptionHandlerRenderError() {
    return function($error, $req, $next) {
        $app = $next->getApp();
        $handler = $app['symfony_exception_handler'];

        $exception = $error->getException() ?: new Lava\Error\ErrorException($error->message);

        return $app->response()->html(
            500,
            [],
            $handler->getHtml($exception)
        );
    };
}
