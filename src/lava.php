<?php

namespace Krak\Lava;

use Krak\Cargo;

function error(...$args) {
    return new Error(...$args);
}
