<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Symfony\Component\Debug;

class ExceptionHandlerPackage extends Lava\AbstractPackage
{
    public function bootstrap(Lava\App $app) {
        Debug\Debug::enable(E_ALL, $app['debug']);
        $handler = Debug\ExceptionHandler::register($app['debug']);
        $app['symfony_exception_handler'] = $handler;

        if ($app['cli']) {
            return;
        }

        $handler->setHandler(function($e) use ($app) {
            $resp = $app->renderError(Lava\Error::createFromException($e), $req);
            $app->emitResponse($resp);
        });
    }

    public function with(Lava\App $app) {
        $app['stacks.render_error']
            ->push(function($error, $req, $next) use ($app) {
                $handler = $app['symfony_exception_handler'];

                $exception = $error->getException() ?: new Lava\Error\ErrorException($error->message);

                return $app->response()->html(
                    500,
                    [],
                    $handler->getHtml($exception)
                );
            });
    }
}
