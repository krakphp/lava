<?php

namespace Krak\Lava;

function error(...$args) {
    return new Error(...$args);
}
