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
describe('->addPath', function() {
    it('adds a path to the application', function() {
        $this->app->addPath('test', __DIR__ . '/test');
        assert(isset($this->app['paths.test']));
    });
});
describe('->hasPath', function() {
    it('determines if a path has been added to the application', function() {
        assert($this->app->hasPath('base'));
    });
});
describe('->path', function() {
    it('returns the named path if no append is provided', function() {
        assert($this->app->path('base') == __DIR__);
    });
    it('returns a joined path if append is provided', function() {
        assert($this->app->path('base', 'a/b') == __DIR__ . '/a/b');
    });
    it('throws an exception if the path is not defined', function() {
        try {
            $this->app->path('bad');
            assert(false);
        } catch (RuntimeException $e) {
            assert(true);
        }
    });
});
foreach (['base', 'resources', 'views', 'config', 'logs'] as $path) {
    describe('->' . $path . 'Path', function() use ($path) {
        it('returns the ' . $path . ' path', function() use ($path) {
            $this->app->addPath($path, $this->app->path('base', $path));
            $full_path = $this->app->{$path.'Path'}('a/b');
            assert($full_path == __DIR__ . "/$path/a/b");
        });
    });
}
