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
