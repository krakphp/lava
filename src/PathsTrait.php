<?php

namespace Krak\Lava;

trait PathsTrait {
    public function path($path = '') {
        if (!$this['base_path']) {
            throw new \RuntimeException('No base_path was provided to this application at construction.');
        }
        if (!$path) {
            return $this['base_path'];
        }

        if ($this->has('paths.' . $path)) {
            $path = $this['paths.'.$path];
        }

        if (is_array($path)) {
            $path = implode(DIRECTORY_SEPARATOR, $path);
        }

        return $this['base_path'] . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
    }
}
