<?php

namespace Krak\Lava\Concerns;

use Krak\EventEmitter\EventEmitter as Emitter;

trait EventEmitter {
    public function on($event, $listener) {
        return $this[Emitter::class]->on($event, $listener);
    }
    public function once($event, $listener) {
        return $this[Emitter::class]->once($event, $listener);
    }
    public function removeListener($event, $listener) {
        return $this[Emitter::class]->removeListener($event, $listener);
    }
    public function removeAllListeners($event = null) {
        return $this[Emitter::class]->removeAllListeners($event);
    }
    public function listeners($event) {
        return $this[Emitter::class]->listeners($event);
    }
    public function emit($event, ...$arguments) {
        return $this[Emitter::class]->emit($event, ...$arguments);
    }
}
