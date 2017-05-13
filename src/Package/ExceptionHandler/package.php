<?php

namespace Krak\Lava\Package\ExceptionHandler;

use Krak\Lava;
use Symfony\Component\Debug\Exception\FlattenException;

function exceptionHandlerRenderError() {
    return function($error, $req, $next) {
        $app = $next->getApp();
        $handler = $app['symfony_exception_handler'];

        $exception = $error->getException() ?: new Lava\Error\ErrorException($error);
        $exception = FlattenException::create($exception, $error->status);

        return $app->response()->html(
            $error->status,
            [],
            $handler->getHtml($exception)
        );
    };
}
