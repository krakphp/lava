<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$app = new Krak\Lava\App();
$app->routes(function($r) {
    $r->get('/{name}', function($name) {
        return "Hello $name!";
    });
});
$app->serve();
