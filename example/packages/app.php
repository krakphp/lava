<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Krak\Lava;

$app = new Lava\App(__DIR__);
$app->with([
    new Lava\Package\EnvPackage(),
    new Lava\Package\ExceptionHandlerPackage(),
]);
$app->routes(function($r) {
    $r->get('/', function($app) {
        return "The .env is located: " . $app->path('.env');
    });
});
$app->serve();
