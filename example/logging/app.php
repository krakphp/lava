<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Krak\Lava;

$app = new Lava\App(__DIR__);
$app->with(new Lava\Package\MonologPackage());
$app->routes(function($r) {
    $r->get('/', function($app) {
        return "Hi!";
    });
});
$app['monolog.rotate_log_files'] = false;
$app['monolog.log_file_name'] = 'app.log';
$app->serve();
