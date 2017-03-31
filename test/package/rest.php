<?php

use Krak\Lava\Package;

it('defaults to json marshaling', function() {
    $this->app->with(new Package\RESTPackage());
    $this->app->routes(function($r) {
        $r->get('/', function() {
            return "a";
        });
    });
    $resp = $this->app->handleRequest();
    assert($resp->getBody() == '"a"' && $resp->getHeaderLine("Content-Type") == "application/json");
});
it('renders errors as json', function() {
    $this->app->with(new Package\RESTPackage());
    $this->app->routes(function($r) {
        $r->get('/', function($app) {
            return $app->abort(500, 'error', 'Test Error');
        });
    });
    $resp = $this->app->handleRequest();
    assert(
        $resp->getStatusCode() === 500 &&
        $resp->getHeaderLine('Content-Type') == 'application/json' &&
        (string) $resp->getBody() == '{"code":"error","message":"Test Error"}'
    );
});
