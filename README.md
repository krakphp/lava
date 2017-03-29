# Lava

Micro-framework with massive potential.

## Installation

Install with composer at `krak/lava`

## Usage

```php
<?php

use Krak\Lava;

$app = new Lava\App();
$app->routes(function($r) {
    $r->get('/', function() {
        return "Hello World!";
    });
});

$app->serve();
```
