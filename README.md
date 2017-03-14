# Lava

Micro-framework with macro potential.

## Usage

```php
<?php

use Krak\Lava;

$app = new Lava\App();
$app->with([
    new Lava\Package\EnvPackage(),
    new Lava\Package\ExceptionHandlerPackage(),
]);
$app->routes(function($r) {
    $r->with('response_factory', 'json');
    $r->get('/hello/{name}', function($name, $app) {
        return ['name' => $name];
    });
    $r->get('/', function() {
        return "Welcome!";
    })->with('response_factory', 'text');
    $r->group('/blog', function($blog) {
        $blog->with('namespace', 'Acme\Http\Controller');
        $blog->get('/{title}', 'BlogController@getIndexAction');
    });
});

$app->serve();
```
