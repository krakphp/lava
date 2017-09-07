<?php

use Krak\Lava\Package;

it('allows view response marshaling', function() {
    $this->app->with(new Package\PlatesPackage());
    $this->app->addPath('views', __DIR__ . '/Resources/plates');
    $this->app['plates.ext'] = 'phtml';
    $this->app->routes(function($r) {
        $r->get('/', function() {
            return ['view', ['title' => 'Foo']];
        });
    });
    $resp = $this->app->handleRequest();
    assert($resp->getBody() == "<h1>Foo</h1>\n");
});
