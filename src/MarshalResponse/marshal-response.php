<?php

namespace Krak\Lava\MarshalResponse;

use Krak\Http;
use Psr\Http\Message\Stream;
use function Krak\Lava\Util\isTuple;

/** determines if response matches an http tuple, if so, it will pass the body along
    to be marshalled and updates the response with the status and headers set.
    Results can be either a 2-tuple or 3-tuple of [status_code, body] or
    [status_code, headers, body]. Downstream marshalers will only receive the body
    as the result
*/
function httpTupleMarshalResponse() {
    return function($res, $req, $next) {
        $valid_http_tuple = isTuple($res, 'integer', 'any') || isTuple($res, "integer", "array", "any");

        $code = $res[0];
        if (!$valid_http_tuple || !($code >= 100 && $code < 600)) {
            return $next($res, $req);
        }

        if (count($res) == 2) {
            $headers = [];
            list($status, $body) = $res;
        } else {
            list($status, $headers, $body) = $res;
        }

        $resp = $next(
            $body,
            $req
        );

        $resp = $resp->withStatus($status);

        foreach ($headers as $name => $value) {
            $resp = $resp->withHeader($name, $value);
        }

        return $resp;
    };
}

function streamMarshalResponse() {
    return function($result, $req, $next) {
        if (!$result instanceof Stream || !is_resource($result)) {
            return $next($result, $req);
        }

        return $next->response(200, [], $result);
    };
}

function redirectMarshalResponse($valid_redirects = [300, 301, 302, 303, 304, 305, 307, 308]) {
    return function($result, $req, $next) use ($valid_redirects) {
        $is_redirect = isTuple($result, "integer", "string");

        if (!$is_redirect || !in_array($result[0], $valid_redirects)) {
            return $next($result, $req);
        }

        list($status, $uri) = $result;
        return $next->response($status, ['Location' => $uri]);
    };
}

function stringMarshalResponse($html = true) {
    return function($result, $req, $next) use ($html) {
        $headers = $html
            ? ['Content-Type' => 'text/html']
            : ['Content-Type' => 'text/plain'];

        return $next->response(200, $headers, $result);
    };
}

function jsonMarshalResponse($options = 0) {
    return function($result, $req, $next) use ($options) {
        return $next->response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($result, $options)
        );
    };
}

/** check the route attributes for a marshal response */
function routeResponseFactoryMarshalResponse() {
    return function($res, $req, $next) {
        $app = $next->getApp();
        $matched = $req->getAttribute('_matched_route');
        $response_factory_type = Http\Route\attributesTreeFirstAttribute($matched->route, 'response_factory');
        if (!$response_factory_type) {
            return $next($res, $req);
        }
        $store = $app->get(Http\ResponseFactoryStore::class);
        $rf = $store->get($response_factory_type);
        if (!$rf) {
            throw new \InvalidArgumentException("response_factory '$response_factory_type' is not registered in the Krak\Http\ResponseFactoryStore");
        }
        return $rf->createResponse(200, [], $res);
    };
}
