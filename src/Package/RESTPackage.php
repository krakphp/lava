<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Krak\Cargo;

use function iter\mapWithKeys, iter\toArray;

class RESTPackage extends Lava\AbstractPackage
{
    public function with(Lava\App $app) {
        // default to json marshaling
        $app['stacks.marshal_response']->push(Lava\MarshalResponse\jsonMarshalResponse());
        $app['stacks.render_error']->push(function($error, $req, $next) {
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
        });
    }
}
