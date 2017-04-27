<?php

use Krak\Lava;

beforeEach(function() {
    $this->app = new Lava\App(__DIR__);
});
describe('Controller', function() {
    describe('->abort', function() {
        it('is an alias to Lava\abort', function() {
            $this->app->routes(function($routes) {
                $routes->get('/', function() {
                    $this->abort(500, 'code', 'message...');
                });
            });
            $resp = $this->app->handleRequest();
            assert($resp->getStatusCode() === 500);
        });
    });
    describe('->response', function() {
        it('returns a response', function() {
            $this->app->routes(function($routes) {
                $routes->get('/', function() {
                    return $this->response()->text(200, [], 'abc');
                });
            });
            $resp = $this->app->handleRequest();
            assert($resp->getStatusCode() == 200 && (string) $resp->getBody() == 'abc');
        });
    });
    describe('->validateQuery', function() {
        it('validates a query of the request', function() {
            $this->app->with(new Lava\Package\RESTPackage());
            $this->app->with(new Lava\Package\ValidationPackage());
            $this->app->routes(function($routes) {
                $routes->get('/', function($req) {
                    $this->validate($req, [
                        'a' => 'string',
                    ]);
                });
            });
            $resp = $this->app->handleRequest();
            assert($resp->getStatusCode() == 422);
        });
    });
});
