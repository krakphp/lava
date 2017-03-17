<?php

namespace Krak\Lava\Middleware;

use Krak\Lava;
use Krak\Http;
use Krak\Mw;
use Psr\Http\Message\ResponseInterface;

use function iter\reduce;

function expectsContentType() {
    return function($req, $next) {
        $matched = $req->getAttribute('_matched_route');
        $expects = Http\Route\attributesTreeFirstAttribute($matched->route, 'expects');
        if (!$expects) {
            return $next($req);
        }

        $ctype = $req->getHeader('Content-Type');
        if (!$ctype || $ctype[0] != $expects) {
            return $next->abort(415, 'unsupported_media_type', 'Expected ' . $expects)->render($req);
        }

        return $next($req);
    };
}

function parseRequestJson() {
    return function($req, $next) {
        $ctype = $req->getHeaderLine('Content-Type');

        if ($ctype == 'application/json') {
            $req = $req->withParsedBody(json_decode($req->getBody(), true));
        }

        return $next($req);
    };
}

function routeMw() {
    return function($req, $next) {
        $app = $next->getApp();
        $compiler = $app[Http\RouteCompiler::class];
        $routes = $compiler->compileRoutes($app[Http\Route\RouteGroup::class], '/');
        $dispatcher = $app[Http\DispatcherFactory::class]->createDispatcher($routes);
        $res = $dispatcher->dispatch($req);
        switch ($res->status_code) {
        case 404:
            return $app->abort(404, 'not_found', 'Page not found.', [
                'endpoint' => $req->getUri()->getPath(),
                'method' => $req->getMethod(),
            ])->render($req);
        case 405:
            $allowed = implode(', ', $res->allowed_methods);
            return $app->abort(405, 'method_not_allowed', 'The method is not allowed on this endpoint', [
                'endpoint' => $req->getUri()->getPath(),
                'method' => $req->getMethod(),
                'allowed_methods' => $allowed,
            ])->render($req)
            ->withAddedHeader('Allow', $allowed);
        }

        $req = $req->withAttribute('_matched_route', $res->matched_route);
        return $next($req);
    };
}

function routingMiddlewareMw() {
    return function($req, $next) {
        $route = $req->getAttribute('_matched_route')->route;

        $middleware = Http\Route\attributesTreeAllAttributes($route, 'middleware');
        $next = $next->chains($middleware);
        return $next($req);
    };
}

function wrapExceptionsToErrors() {
    return function($req, $next) {
        try {
            return $next($req);
        } catch (\Exception $e) {
            return $next->getApp()->renderError(Lava\Error::createFromException($e), $req);
        }
    };
}

function invokeMw() {
    return function($req, $next) {
        $app = $next->getApp();
        $matched_route = $req->getAttribute('_matched_route');
        $invoke_action = $app->compose([
            Mw\guard('No invoke_action handler was able to invoke the given action.'),
            $app['stacks.invoke_action']
        ]);
        $resp = $invoke_action($matched_route, $req);
        if ($resp instanceof ResponseInterface) {
            return $resp;
        }

        $marshal = $app->compose([
            Mw\guard('No marshal_response handler was able to marshal the given controller response. Check your controller response and make sure it can be used by the provided marshalers.'),
            $app['stacks.marshal_response']
        ]);
        return $marshal($resp, $req);
    };
}
