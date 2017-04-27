<?php

namespace Krak\Lava;

use ArrayObject;
use Krak\Cargo;
use Krak\Http;
use Krak\AutoArgs;
use Krak\EventEmitter;
use Krak\Invoke;
use Krak\Mw;
use Zend\Diactoros;
use Psr\Log;
use Psr\Http\Message\ServerRequestInterface;
use Symfony;

use function Krak\Lava\Middleware\routeMw,
    Krak\Lava\Middleware\routingMiddlewareMw,
    Krak\Lava\Middleware\invokeMw;

class LavaPackage extends AbstractPackage
{
    public function bootstrap(App $app) {
        $app->on(Events::FREEZE, function($app) {
            $app->routesStack()->unshift(routingMiddlewareMw())
                ->push(routeMw($app));
            $app->httpStack()
                ->unshift($app->routesStack())
                ->unshift(invokeMw($app));
        });
    }

    public function with(App $app) {
        $app->protect('compose', Mw\composer(
            new Middleware\LavaContext($app),
            Middleware\LavaLink::class
        ));

        $app->addStack('http');
        $app->addStack('routes');
        $app->addStack('invoke_action');
        $app->addStack('marshal_response');
        $app->addStack('render_error');

        $app->httpStack()->push(Middleware\wrapExceptionsToErrors())
            ->push(Middleware\logRequestResponse(), 1);
        $app->routesStack()->fill([
            Middleware\parseRequestJson(),
            Middleware\expectsContentType()
        ]);
        $app->invokeActionStack()
            ->push(InvokeAction\callableInvokeAction(), 0, 'callable')
            ->push(InvokeAction\controllerMethodInvokeAction('@'), 0, 'controllerMethod')
            ->push(InvokeAction\prefixInvokeAction(), 0, 'prefix');
        $app->marshalResponseStack()
            ->push(MarshalResponse\routeResponseFactoryMarshalResponse(), 1, 'routeResponseFactory')
            ->push(MarshalResponse\streamMarshalResponse(), 1, 'stream')
            ->push(MarshalResponse\httpTupleMarshalResponse(), 1, 'httpTuple')
            ->push(MarshalResponse\redirectMarshalResponse(), 1, 'redirect')
            ->push(MarshalResponse\errorMarshalResponse(), 1, 'error')
            ->push(MarshalResponse\jsonMarshalResponse(), 0, 'json')
            ->push(MarshalResponse\stringMarshalResponse(), 0, 'string');
        $app->renderErrorStack()
            ->push(Error\logRenderError(), 1, 'log')
            ->push(Error\textRenderError(), 0, 'text');

        if ($app->hasPath('base')) {
            $app->addPath('resources', $app->basePath('resources'));
            $app->addPath('views', $app->resourcesPath('views'));
            $app->addPath('logs', $app->basePath(['var', 'log']));
            $app->addPath('config', $app->basePath('config'));
        }
    }

    public function register(Cargo\Container $c) {
        $c[Http\ResponseFactory::class] = function() {
            return new Http\ResponseFactory\DiactorosResponseFactory();
        };
        $c[Http\ResponseFactoryStore::class] = function($c) {
            $store = new Http\ResponseFactoryStore();
            $rf = $c[Http\ResponseFactory::class];
            $store->store('json', new Http\ResponseFactory\JsonResponseFactory(
                $rf,
                $c['json_encode_options']
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
        $c->factory(ServerRequestInterface::class, function() {
            return Diactoros\ServerRequestFactory::fromGlobals();
        });
        $c[Http\Server::class] = function($app) {
            return new Http\Server\DiactorosServer(
                $app[Diactoros\Response\EmitterInterface::class],
                function() use ($app) {
                    return $app[ServerRequestInterface::class];
                }
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
        $c[EventEmitter\EventEmitter::class] = function($c) {
            $invoke = EventEmitter\emitterInvoke();
            $invoke = new Invoke\ContainerInvoke($invoke, $c->toInterop());
            return EventEmitter\emitter($invoke);
        };
        $c[Log\LoggerInterface::class] = function() {
            return new Log\NullLogger();
        };
        $c->alias(Console\Application::class, 'Symfony\Component\Console\Application', 'console');
        $c->alias(ServerRequestInterface::class, 'request');

        $c['commands'] = new ArrayObject();
        $c['bootstrapped'] = false;
        $c['bootstrappers'] = new ArrayObject();
        $c['frozen'] = false;
        $c['debug'] = false;
        $c['version'] = '0.3.2';
        $c['name'] = 'Lava';
        $c['cli'] = PHP_SAPI === 'cli';
        $c['json_encode_options'] = 0;
    }
}
