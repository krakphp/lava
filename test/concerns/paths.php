<?php

beforeEach(function() {
    $this->app->addPath('base', __DIR__);
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
