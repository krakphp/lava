<?php

use Eloquent\Phony\Peridot\PeridotPhony;
use Evenement\EventEmitterInterface;

return function (EventEmitterInterface $emitter) {
    PeridotPhony::install($emitter);
};
