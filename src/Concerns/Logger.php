<?php

namespace Krak\Lava\Concerns;

use Psr\Log;

trait Logger
{
    use Log\LoggerTrait;

    public function log($level, $message, array $context = array()) {
        return $this[Log\LoggerInterface::class]->log($level, $message, $context);
    }
}
