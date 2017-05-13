<?php

namespace Krak\Lava\Package\Validation;

use Krak\Validation\Exception\ViolationException;
use Krak\Lava;

class ConvertViolationExceptionRenderError
{
    public function handle($error, $req, $next) {
        if (!$error->getException() || !$error->getException() instanceof ViolationException) {
            return $next($error, $req);
        }

        return $next(Lava\error(422, 'failed_validation', 'The request failed validation.', [
            'errors' => $error->getException()->violation->flatten()->format()
        ]), $req);
    }
}
