<?php

use Krak\Lava;

require_once __DIR__ . '/../../vendor/autoload.php';

$app = new Lava\App(__DIR__);
$app->with(new Lava\Package\ExceptionHandlerPackage());
$app['debug'] = true;
$app->routes(function($routes) {
    $routes->get('/', function() {
        $links = <<<HTML
<a href="/error">Error</a>
<a href="/abort">Abort</a>
HTML;

        return $links;
    });
    $routes->get('/error', function() {
        return Lava\error(500, 'test', 'Test Error');
    });
    $routes->get('/abort', function() {
        Lava\abort(500, 'test', 'Test Abort');
    });
});
$app->serve();
