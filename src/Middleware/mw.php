<?php

namespace Krak\Lava\Middleware;

use Krak\Lava;
use Krak\Http;
use Psr\Http\Message\ResponseInterface;

use function iter\reduce;

function routeMw(Lava\App $c) {
    return function($req, $next) use ($c) {
        $compiler = $c[Http\RouteCompiler::class];
        $routes = $compiler->compileRoutes($c[Http\Route\RouteGroup::class], '/');
        $dispatcher = $c[Http\DispatcherFactory::class]->createDispatcher($routes);
        $res = $dispatcher->dispatch($req);
        switch ($res->status_code) {
        case 404: throw new Http\Exception\HttpException(404, 'Not Found');
        case 405: throw new Http\Exception\HttpException(405, 'Method not allowed', ['Allow' => implode(', ', $res->allowed_methods)]);
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

function invokeMw(Lava\App $app) {
    return function($req, $next) use ($app) {
        $matched_route = $req->getAttribute('_matched_route');
        $invoke_action = $app->compose([$app['stacks.invoke_action']]);
        $resp = $invoke_action($matched_route, $req);
        if ($resp instanceof ResponseInterface) {
            return $resp;
        }

        $marshal = $app->compose([$app['stacks.marshal_response']]);
        return $marshal($resp, $req);
    };
}
