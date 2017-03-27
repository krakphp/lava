<?php

namespace Krak\Lava;

trait PathsTrait {
    public function addPath($name, $path) {
        $this['paths.' . $name] = rtrim($path, DIRECTORY_SEPARATOR);
    }
    public function hasPath($name) {
        return $this->has('paths.' . $name);
    }
    public function path($name, $appended_path = '') {
        if (!$this->has('paths.' . $name)) {
            throw new \RuntimeException("Path '$name' was not added to this application.");
        }

        $path = $this['paths.' . $name];

        if (!$appended_path) {
            return $path;
        }

        $appended_path = is_array($appended_path)
            ? implode(DIRECTORY_SEPARATOR, $appended_path)
            : $appended_path;

        return $path . DIRECTORY_SEPARATOR . ltrim($appended_path, DIRECTORY_SEPARATOR);
    }

    public function basePath($appended = '') {
        return $this->path('base', $appended);
    }

    public function resourcesPath($appended = '') {
        return $this->path('resources', $appended);
    }

    public function viewsPath($appended = '') {
        return $this->path('views', $appended);
    }

    public function logsPath($appended = '') {
        return $this->path('logs', $appended);
    }

    public function configPath($appended = '') {
        return $this->path('config', $appended);
    }
}
