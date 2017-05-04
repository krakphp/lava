<?php

namespace Krak\Lava\Middleware;

use Krak\Mw;
use Krak\Http\Middleware\HttpLink;
use Psr\Log;

class LavaLink extends HttpLink implements Log\LoggerInterface
{
    use Log\LoggerTrait;

    public function getApp() {
        return $this->getContext()->getApp();
    }

    public function abort(...$args) {
        return $this->getApp()->abort(...$args);
    }

    public function log($level, $message, array $context = array()) {
        return $this->getApp()->log($level, $message, $context);
    }
}
