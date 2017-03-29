<?php

use Zend\Diactoros\Response\EmitterInterface;
use Psr\Http\Message\ResponseInterface;
use Krak\Lava;
use Krak\Http;

describe('->response', function() {
    it('creates a response', function() {
        $resp = $this->app->response(404, [], 'body');
        assert($resp->getStatusCode() == 404 && (string) $resp->getBody() == 'body');
    });
    it('returns the response factory store on no arguments', function() {
        assert($this->app->response() instanceof Http\ResponseFactoryStore);
    });
});
describe('->serve', function() {
    it('bootstraps, freezes, serves, and terminates a request', function() {
        $this->app->wrap(EmitterInterface::class, function($emitter, $app) {
            return new class($app) implements EmitterInterface {
                private $app;
                public function __construct($app) {
                    $this->app = $app;
                }
                public function emit(ResponseInterface $resp) {
                    $this->app['_response'] = $resp;
                }
            };
        });
        $this->app->routes(function($routes) {
            $routes->get('/', function($app) {
                return $app->response(200, [], 'body');
            });
        });
        $this->app->serve();
        assert((string) $this->app['_response']->getBody() === 'body');
    });
});
