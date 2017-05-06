<?php

namespace Krak\Lava\Package\Validation;

use Krak\Validation\Exception\ViolationException;

class ConvertViolationExceptionMiddleware
{
    public function handle($req, $next) {
        try {
            return $next($req);
        } catch (ViolationException $e) {
            return $next->abort(422, 'failed_validation', 'The request failed validation.', [
                'errors' => $e->violation->flatten()->format(),
            ])->render($req);
        }
    }
}
