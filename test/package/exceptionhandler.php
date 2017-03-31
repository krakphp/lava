<?php

use Krak\Lava\Package;

it('registers an exception handler error renderer', function() {
    $this->app->with(new Package\ExceptionHandlerPackage());
    assert($this->app->renderErrorStack()->has('exceptionHandler'));
});
it('regiters an exception handler on bootstrap', function() {
    $this->app['debug'] = true;
    $this->app['cli'] = false;
    $this->app->with(new Package\ExceptionHandlerPackage());
    $this->app->routes(function($r) {
        $r->get('/', function() {
            return $a;
        });
    });
    $resp = $this->app->handleRequest();
    assert($resp->getStatusCode() === 500);
});
