<?php

namespace Krak\Lava;

trait PathsTrait {
    public function path($path = '') {
        if (!$path) {
            return $this['base_path'];
        }

        if (is_array($path)) {
            $path = implode(DIRECTORY_SEPARATOR, $path);
        }

        return $this['base_path'] . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR); 
    }
}
