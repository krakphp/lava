<?php

namespace Krak\Lava\Error;

use Krak\Lava;
use Psr\Http\Message\ServerRequestInterface;

function _encodePayload($payload) {
    return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}

function textRenderError() {
    return function(Lava\Error $error, ServerRequestInterface $req, $next) {
        $payload = _encodePayload($error->payload);
        $content = <<<CONTENT
code: $error->code
message: $error->message
CONTENT;

        $exception = $error->getException();
        if ($exception && $next->getApp()['debug']) {
            $content .= "\n" . $exception;
        }

        return $next->response()->text(
            500,
            [],
            $content
        );
    };
}
