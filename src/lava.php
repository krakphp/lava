<?php

namespace Krak\Lava;

function app($service, callable $create, $name = 'default') {
    static $app_store;

    if (!$app_store) {
        $app_store = [];
    }

    if (!isset($app_store[$name])) {
        $app_store[$name] = $create();
    }

    $app = $app_store[$name];

    if ($service) {
        return $app[$service];
    }

    return $app;
}

function error(...$args) {
    return new Error(...$args);
}
