<?php

namespace Krak\Lava\Middleware;

use Krak\Mw;
use Psr\Log;

class LavaLink extends Mw\Link\ContainerLink implements Log\LoggerInterface
{
    use Log\LoggerTrait;

    public function getApp() {
        return $this->getContext()->getApp();
    }

    public function abort(...$args) {
        return $this->getApp()->abort(...$args);
    }

    public function response(...$args) {
        return $this->getApp()->response(...$args);
    }

    public function log($level, $message, array $context = array()) {
        return $this->getApp()->log($level, $message, $context);
    }
}
