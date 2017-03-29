<?php

namespace Krak\Lava\Concerns;

use Krak\Mw;

trait Stacks {
    public function addStack($name, $entries = []) {
        $this['stacks.' . $name] = Mw\stack($entries);
    }
    public function hasStack($name) {
        return $this->has('stacks.' . $name);
    }
    public function stack($name) {
        if (!$this->hasStack($name)) {
            throw new \RuntimeException("Stack '$name' was not added to this application.");
        }
        return $this['stacks.' . $name];
    }

    public function httpStack() {
        return $this->stack('http');
    }
    public function routesStack() {
        return $this->stack('routes');
    }
    public function invokeActionStack() {
        return $this->stack('invoke_action');
    }
    public function marshalResponseStack() {
        return $this->stack('marshal_response');
    }
    public function renderErrorStack() {
        return $this->stack('render_error');
    }
}
