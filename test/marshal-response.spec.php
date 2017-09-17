<?php

use Krak\Lava;

describe('Krak Lava MarshalResponse', function() {
    beforeEach(function() {
        $this->app = new Lava\App();
    });
    describe('#streamMarshalResponse', function() {
        it('will marshal a response if a php stream', function() {
            $marshal = Lava\MarshalResponse\streamMarshalResponse();
            $marshal = $this->app->compose([
                function($res, $req, $next) {
                    return $next->response(200, [], '');
                },
                $marshal
            ]);
            $stream = fopen("php://temp", "rw");
            fwrite($stream, "foo");
            rewind($stream);
            $response = $marshal($stream, $this->app['request']);
            assert($response->getBody()->getContents() == 'foo');
        });
        it('will pass through if not a stream', function() {
            $marshal = Lava\MarshalResponse\streamMarshalResponse();
            $marshal = $this->app->compose([
                function($res, $req, $next) {
                    return $next->response(200, [], 'foo');
                },
                $marshal
            ]);
            $response = $marshal('', $this->app['request']);
            assert($response->getBody()->getContents() == 'foo');
        });
    });
});
