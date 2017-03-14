<?php

namespace Krak\Lava;

use Psr\Http\Message\ServerRequestInterface;

class Error
{
    public $status;
    public $code;
    public $message;
    public $payload;
    public $app;

    public function __construct($status = 500, $code = 'error', $message = 'Error', array $payload = []) {
        $this->status = $status;
        $this->code = $code;
        $this->message = $message;
        $this->payload = $payload;
    }

    public function withStatus($status) {
        $err = clone $this;
        $err->status = $status;
        return $err;
    }

    public function withCode($code) {
        $err = clone $this;
        $err->code = $code;
        return $err;
    }

    public function withMessage($message) {
        $err = clone $this;
        $err->message = $message;
        return $err;
    }

    public function with($key, $value = null) {
        $err = clone $this;
        if (is_array($key)) {
            $err->payload = array_merge($err->payload, $key);
        } else {
            $err->payload[$key] = $value;
        }
        return $err;
    }

    public function withPayload(array $payload) {
        $err = clone $this;
        $err->payload = $payload;
        return $err;
    }

    public function getException() {
        return isset($this->payload['_exception']) ? $this->payload['_exception'] : null;
    }
    public static function createFromException(\Exception $e) {
        return new self(500, 'exception', $e->getMessage(), ['_exception' => $e]);
    }
}
