<?php

use Krak\Lava\Package;

it('loads env vars from the .env', function() {
    $this->app->addPath('base', __DIR__ . '/Resources/env');
    $this->app->with(new Package\EnvPackage());
    $this->app->bootstrap();
    assert(getenv('FOO') == 'bar');
});
