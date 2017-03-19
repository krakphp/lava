<?php

namespace Krak\Lava;

use Krak\EventEmitter\EventEmitter;

trait EventEmitterTrait {
    public function on($event, $listener) {
        return $this[EventEmitter::class]->on($event, $listener);
    }
    public function once($event, $listener) {
        return $this[EventEmitter::class]->once($event, $listener);
    }
    public function removeListener($event, $listener) {
        return $this[EventEmitter::class]->removeListener($event, $listener);
    }
    public function removeAllListeners($event = null) {
        return $this[EventEmitter::class]->removeAllListeners($event);
    }
    public function listeners($event) {
        return $this[EventEmitter::class]->listeners($event);
    }
    public function emit($event, ...$arguments) {
        return $this[EventEmitter::class]->emit($event, ...$arguments);
    }
}
