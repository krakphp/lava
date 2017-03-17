<?php

namespace Krak\Lava\Util;

function isTuple($tuple, ...$types) {
    if (!is_array($tuple) || count($tuple) != count($types)) {
        return false;
    }

    foreach ($types as $i => $type) {
        if (
            !isset($tuple[$i]) ||
            !($type == "any" || gettype($tuple[$i]) == $type)
        ) {
            return false;
        }
    }

    return true;
}

function typeToString($type) {
    return is_object($type) ? get_class($type) : gettype($type);
}

function isStringy($var) {
    return $var === null || is_scalar($var) || (is_object($var) && method_exists($var, '__toString'));
}
