<?php

namespace Krak\Lava\Package\REST;

function restRenderError() {
    return function($error, $req, $next) {
        $app = $next->getApp();
        $body = [
            'code' => $error->code,
            'message' => $error->message,
        ];

        if (!$app['debug']) {
            $payload = $error->payload;
            unset($payload['_exception']);
            $error = $error->withPayload($payload);
        }

        $exception = $error->getException();
        if ($exception) {
            $body['exception'] = (string) $exception;
        } else {
            $body = array_merge($body, $error->payload);
        }

        return $next->response()->json(
            $error->status,
            [],
            $body
        );
    };
}
