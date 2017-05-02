<?php

namespace Krak\Lava\Http;

use Psr\Http\Message\ServerRequestInterface;
use Krak\Lava;

class Controller
{
    protected $lava;

    public function setLava(Lava\App $lava) {
        $this->lava = $lava;
    }

    public function getApp() {
        return $this->lava;
    }

    public function abort(...$args) {
        Lava\abort(...$args);
    }

    public function error(...$args) {
        return Lava\error(...$args);
    }

    public function response(...$args) {
        return $this->lava->response(...$args);
    }

    public function validate($data, $validators) {
        $validator = $this->lava['validation']->make($validators);
        $violations = $validator->validate($data);

        if (!$violations) {
            return;
        }

        $this->abort(422, 'failed_validation', 'The request failed validation.', [
            'errors' => $violations->format(),
        ]);
    }

    public function validateQuery(ServerRequestInterface $req, $validators) {
        return $this->validate($req->getQueryParams(), $validators);
    }

    public function validateBody(ServerRequestInterface $req, $validators) {
        return $this->validate($req->getParsedBody(), $validators);
    }
}
