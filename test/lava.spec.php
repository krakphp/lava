<?php

describe('Krak Lava', function() {
    describe('App', function() {
        require_once __DIR__ . '/app.php';
    });
    describe('Concerns', function() {
        beforeEach(function() {
            $this->app = new Krak\Lava\App(__DIR__);
        });
        foreach (['Http', 'Paths', 'Stacks'] as $name) {
            describe($name, function() use ($name) {
                $name = strtolower($name);
                require_once __DIR__ . "/concerns/$name.php";
            });
        }
    });
    describe('Package', function() {
        beforeEach(function() {
            $this->app = new Krak\Lava\App(__DIR__ . '/package');
        });
        foreach (['REST', 'Plates', 'ExceptionHandler', 'Env'] as $name) {
            describe($name . 'Package', function() use ($name) {
                $name = strtolower($name);
                require_once __DIR__ . "/package/$name.php";
            });
        }
    });
});
