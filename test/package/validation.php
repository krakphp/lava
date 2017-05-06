<?php

use Krak\Lava\Package;
use Krak\Validation\Validators as Assert;
use Krak\Validation\FormatMessage\StrTrFormatMessage;

it('registers the validation kernel', function() {
    $this->app->with(new Package\ValidationPackage());
    assert($this->app->has('validation'));
});
it('converts violation exceptions to rendered errors', function() {
    $this->app->with(new Package\ValidationPackage());
    $this->app->routes(function($routes) {
        $routes->get('/', function() {
            $validation = $this->getApp()['validation'];
            $validator = $validation->make('string');
            $validator->validate(1)->abort();
        });
    });
    $resp = $this->app->handleRequest();
    assert($resp->getStatusCode() == 422);
});
