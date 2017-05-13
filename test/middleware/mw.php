<?php

use Zend\Diactoros\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Krak\Lava;
use Krak\Http;

beforeEach(function() {
    $this->app = new Krak\Lava\App(__DIR__);
});

describe('#expectsContentType', function() {
    beforeEach(function() {
        $this->app->routes(function($routes) {
            $routes->get('/', function() {
                return "abc";
            })->with('expects', 'application/json');
        });
    });
    it('checks nothing if no content-type exists', function() {
        $req = new ServerRequest([], [], '/', 'GET');
        $resp = $this->app->handleRequest($req);
        assert($resp->getStatusCode() == 200);
    });
    it('passes through if the content type matches', function() {
        $req = new ServerRequest([], [], '/', 'GET', 'php://temp', ['Content-Type' => 'application/json;charset=UTF-8']);
        $resp = $this->app->handleRequest($req);
        assert($resp->getStatusCode() == 200);
    });
    it('returns a 415 error if content type does not match', function() {
        $req = new ServerRequest([], [], '/', 'GET', 'php://temp', ['Content-Type' => 'text/html;charset=UTF-8']);
        $resp = $this->app->handleRequest($req);
        assert($resp->getStatusCode() == 415);
    });
});
describe('#parseJson', function() {
    it('parses JSON requests into the body', function() {
        $this->app->routes(function($routes) {
            $routes->post('/', function($req) {
                $data = $req->getParsedBody();
                assert($data[0] == 1 && $data[1] == 2);
                return "";
            });
        });
        $stream = fopen("php://temp", "rw");
        fwrite($stream, "[1,2]");
        rewind($stream);
        $req = new ServerRequest([], [], '/', 'POST', $stream, ['Content-Type' => 'application/json;charset=UTF-8']);
        $resp = $this->app->handleRequest($req);
        assert($resp->getStatusCode() == 200);
    });
});
describe('RenderHttpExceptionsLink', function() {
    it('renders http stack exceptions', function() {
        $this->app->httpStack()->push(function($req, $next) {
            throw new \Exception('rendered');
            return $next($req);
        });
        $resp = $this->app->handleRequest();
        assert($resp->getStatusCode() == 500 && $resp->getBody() == "code: exception\nmessage: rendered");
    });
});
