<?php

namespace Krak\Lava\Middleware;

use Krak\Mw;
use Krak\Http\Middleware\HttpLink;
use Psr\Log;
use Krak\Lava;

class RenderHttpExceptionsLink extends LavaLink
{
    public function __invoke(...$params) {
        try {
            return parent::__invoke(...$params);
        } catch (\Exception $e) {

        } catch(\Throwable $e) {
            $e = new \Symfony\Component\Debug\Exception\FatalThrowableError($e);
        }

        $this->debug('Wrapping caught exception into error');
        return $this->getApp()->renderError(Lava\Error::createFromException($e), $params[0]);
    }
}
