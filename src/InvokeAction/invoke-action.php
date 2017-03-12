<?php

namespace Krak\Lava\InvokeAction;

use Krak\Lava;
use Krak\Http;
use Krak\AutoArgs;
use Krak\Cargo;
use Psr\Http\Message\ServerRequestInterface;

function callableInvokeAction() {
    return function(Http\Dispatcher\MatchedRoute $matched, ServerRequestInterface $req, $next) {
        $app = $next->getApp();

        $handler = $matched->route->handler;
        $auto_args = $app[AutoArgs\AutoArgs::class];

        if (is_array($handler) && is_string($handler[0]) && $app->has($handler[0])) {
            $handler[0] = $app[$handler[0]];
        }

        if (!is_callable($handler)) {
            throw new \InvalidArgumentException('Route handler is not callable');
        }

        return $auto_args->invoke($handler, [
            'objects' => [$matched, $req, $app],
            'vars' => array_merge([
                'req' => $req,
                'request' => $req,
                'app' => $app,
            ], $matched->params),
            'container' => Cargo\toInterop($app)
        ]);
    };
}

/** checks to see if an action is actually a controller and method to be invoked */
function controllerMethodInvokeAction($separator = '@') {
    return function(Http\Dispatcher\MatchedRoute $matched, ServerRequestInterface $req, $next) use ($separator) {
        $handler = $matched->route->handler;
        if (!is_string($handler) || strpos($handler, $separator) === false) {
            return $next($matched, $req);
        }

        list($controller, $method) = explode($separator, $matched->route->handler);
        $matched = $matched->withRoute($matched->route->withHandler([$controller, $method]));
        return $next($matched, $req);
    };
}

function prefixInvokeAction() {
    return function(Http\Dispatcher\MatchedRoute $matched, ServerRequestInterface $req, $next) {
        $route = $matched->route;
        $attributes = $route->getAttributes();

        if (!is_string($route->handler) || is_callable($route->handler)) {
            return $next($matched, $req);
        }

        $prefix = Http\Route\attributesTreeFirstAttribute($route, 'prefix');
        $namespace = Http\Route\attributesTreeFirstAttribute($route, 'namespace');

        if ($prefix) {
            $matched = $matched->withRoute($route->withHandler(
                $prefix . $route->handler
            ));
        } else if ($namespace) {
            $matched = $matched->withRoute($route->withHandler(
                rtrim($namespace, '\\') . '\\' . ltrim($route->handler, '\\')
            ));
        }

        return $next($matched, $req);
    };
}
