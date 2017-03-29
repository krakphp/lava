<?php

use Krak\Lava;
use Krak\Cargo;

beforeEach(function() {
    $this->app = new Lava\App(__DIR__);
});
describe('->with', function() {
    it('registers service providers', function() {
        $provider = new class() implements Cargo\ServiceProvider {
            public function register(Cargo\Container $c) {
                $c['a'] = 1;
            }
        };
        $this->app->with($provider);
        assert($this->app['a'] === 1);
    });
    it('registers callables as packages', function() {
        $this->app->with(function($app) {
            $app['b'] = 2;
        });
        assert($this->app['b'] == 2);
    });
    it('registers packages', function() {
        $this->app->with(new class implements Lava\Package {
            public function with(Lava\App $app) {
                $app['a'] = 3;
            }
        });
        assert($this->app['a'] == 3);
    });
    it('registers bootstraps', function() {
        $package = new class() implements Lava\Bootstrap {
            public function bootstrap(Lava\App $app) {
                $app['a'] = 5;
            }
        };
        $this->app->with($package);
        assert($this->app->has('a') === false);
        $this->app->bootstrap();
        assert($this->app['a'] == 5);
    });
});
