<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Symfony\Component\Debug;

class ExceptionHandlerPackage implements Lava\Bootstrap
{
    public function bootstrap(Lava\App $app) {
        Debug\Debug::enable($app['debug']);
        $handler = Debug\ExceptionHandler::register();

        if ($app['cli']) {
            return;
        }

        $handler->setHandler(function($e) use ($app) {
            $render = $app->compose([$app['stacks.exception']]);
            $resp = $render($e);
            $app->emitResponse($resp);
        });
    }
}
