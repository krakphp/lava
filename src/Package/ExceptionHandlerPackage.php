<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Symfony\Component\Debug;

class ExceptionHandlerPackage extends Lava\AbstractPackage
{
    public function bootstrap(Lava\App $app) {
        Debug\Debug::enable(E_ALL, $app['debug']);
        if ($app['cli']) {
            return;
        }

        $handler = Debug\ExceptionHandler::register($app['debug']);
        $app['symfony_exception_handler'] = $handler;

        $handler->setHandler(function($e) use ($app) {
            $resp = $app->renderError(Lava\Error::createFromException($e), $req);
            $app->emitResponse($resp);
        });
    }

    public function with(Lava\App $app) {
        $app->renderErrorStack()->push(ExceptionHandler\exceptionHandlerRenderError(), 0, 'exceptionHandler');
    }
}
