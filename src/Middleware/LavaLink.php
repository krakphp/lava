<?php

namespace Krak\Lava\Middleware;

use Krak\Mw;

class LavaLink extends Mw\Link\ContainerLink
{
    public function getApp() {
        return $this->getContext()->getApp();
    }

    public function abort(...$args) {
        return $this->getApp()->abort(...$args);
    }

    public function response(...$args) {
        return $this->getApp()->response(...$args);
    }
}
