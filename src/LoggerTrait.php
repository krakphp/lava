<?php

namespace Krak\Lava;

use Psr\Log;

trait LoggerTrait
{
    use Log\LoggerTrait;

    public function log($level, $message, array $context = array()) {
        return $this[Log\LoggerInterface::class]->log($level, $message, $context);
    }
}
