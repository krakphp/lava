<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Krak\Lava;

$app = new Lava\App(__DIR__);
$app->with([
    new Lava\Package\RESTPackage(),
]);
$app->routes(function($r) {
    $r->get('/', function($app) {
        return [1,2,3];
    });
});
$app['json_encode_options'] = JSON_PRETTY_PRINT;
$app->serve();
