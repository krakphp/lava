<?php

describe('Krak Lava', function() {
    describe('App', function() {
        require_once __DIR__ . '/app.php';
    });
    describe('Concerns', function() {
        beforeEach(function() {
            $this->app = new Krak\Lava\App(__DIR__);
        });
        describe('Http', function() {
            require_once __DIR__ . '/concerns/http.php';
        });
        describe('Paths', function() {
            require_once __DIR__ . '/concerns/paths.php';
        });
        describe('Stacks', function() {
            require_once __DIR__ . '/concerns/stacks.php';
        });
    });
});
