<?php

namespace Krak\Lava;

use Evenement\EventEmitterInterface;

trait EventEmitterTrait {
    public function on($event, callable $listener) {
        return $this[EventEmitterInterface::class]->on($event, $listener);
    }
    public function once($event, callable $listener) {
        return $this[EventEmitterInterface::class]->once($event, $listener);
    }
    public function removeListener($event, callable $listener) {
        return $this[EventEmitterInterface::class]->removeListener($event, $listener);
    }
    public function removeAllListeners($event = null) {
        return $this[EventEmitterInterface::class]->removeAllListeners($event);
    }
    public function listeners($event) {
        return $this[EventEmitterInterface::class]->listeners($event);
    }
    public function emit($event, array $arguments = []) {
        return $this[EventEmitterInterface::class]->emit($event, $arguments);
    }
}
