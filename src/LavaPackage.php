<?php

namespace Krak\Lava;

use ArrayObject;
use Krak\Cargo;
use Krak\Http;
use Krak\AutoArgs;
use Krak\Mw;
use Zend\Diactoros;
use Evenement;
use Psr\Log;
use Symfony;

use function Krak\Lava\Middleware\routeMw,
    Krak\Lava\Middleware\routingMiddlewareMw,
    Krak\Lava\Middleware\invokeMw;

class LavaPackage implements Package, Cargo\ServiceProvider
{
    public function with(App $app) {
        $app->on(Events::FREEZE, function($app) {
            $app['stacks.routes']->unshift(routingMiddlewareMw())
                ->push(routeMw($app));
            $app['stacks.http']
                ->unshift($app['stacks.routes'])
                ->unshift(invokeMw($app));
        });

        $app['stacks.invoke_action']
            ->push(InvokeAction\callableInvokeAction(), 0, 'callable')
            ->push(InvokeAction\controllerMethodInvokeAction('@'), 0, 'controllerMethod')
            ->push(InvokeAction\prefixInvokeAction(), 0, 'prefix');
        $app['stacks.marshal_response']
            ->push(MarshalResponse\routeResponseFactoryMarshalResponse(), 1, 'routeResponseFactory')
            ->push(MarshalResponse\streamMarshalResponse(), 1, 'stream')
            ->push(MarshalResponse\httpTupleMarshalResponse(), 1, 'httpTuple')
            ->push(MarshalResponse\redirectMarshalResponse(), 1, 'redirect')
            ->push(MarshalResponse\stringMarshalResponse());
    }

    public function register(Cargo\Container $c) {
        $c[Http\ResponseFactory::class] = function() {
            return new Http\ResponseFactory\DiactorosResponseFactory();
        };
        $c['krak.http.response_factory.json_encode_options'] = 0;
        $c[Http\ResponseFactoryStore::class] = function($c) {
            $store = new Http\ResponseFactoryStore();
            $rf = $c[Http\ResponseFactory::class];

            $store->store('json', new Http\ResponseFactory\JsonResponseFactory(
                $rf,
                $c['krak.http.response_factory.json_encode_options']
            ));
            $store->store('html', new Http\ResponseFactory\HtmlResponseFactory($rf));
            $store->store('text', new Http\ResponseFactory\TextResponseFactory($rf));

            return $store;
        };
        $c[Http\RouteCompiler::class] = function() {
            return new Http\Route\RecursiveRouteCompiler();
        };
        $c[Http\DispatcherFactory::class] = function() {
            return new Http\Dispatcher\FastRoute\FastRouteDispatcherFactory();
        };
        $c[Diactoros\Response\EmitterInterface::class] = function() {
            return new Diactoros\Response\SapiEmitter();
        };
        $c[Http\Server::class] = function($app) {
            return new Http\Server\DiactorosServer(
                $app[Diactoros\Response\EmitterInterface::class]
            );
        };
        $c[Http\Route\RouteGroup::class] = function() {
            return new Http\Route\RouteGroup('');
        };

        $c[AutoArgs\AutoArgs::class] = function() {
            return new AutoArgs\AutoArgs();
        };
        $c[Console\Application::class] = function($c) {
            return new Console\Application($c);
        };
        $c[Evenement\EventEmitterInterface::class] = function() {
            return new Evenement\EventEmitter();
        };
        $c[Log\LoggerInterface::class] = function() {
            return new Log\NullLogger();
        };
        $c->alias(Console\Application::class, 'Symfony\Component\Console\Application', 'console');
        $c->alias(Evenement\EventEmitterInterface::class, 'emitter', 'event_emitter');
        $c->alias(Log\LoggerInterface::class, 'log', 'logger');

        $c['packages'] = new ArrayObject();
        $c['commands'] = new ArrayObject();
        $c['debug'] = false;
        $c['frozen'] = false;

        $c['stacks.http'] = mw\stack();
        $c['stacks.routes'] = mw\stack();
        $c['stacks.exception'] = mw\stack();
        $c['stacks.invoke_action'] = mw\stack();
        $c['stacks.marshal_response'] = mw\stack();
        $c->protect('compose', Mw\composer(
            new Middleware\LavaContext($c),
            Middleware\LavaLink::class
        ));
    }
}
