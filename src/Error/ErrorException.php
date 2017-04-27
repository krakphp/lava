<?php

namespace Krak\Lava\Error;

use Krak\Lava;

class ErrorException extends \RuntimeException
{
    public $error;

    public function __construct(Lava\Error $error) {
        parent::__construct($error->message);
        $this->error = $error;
    }
}
