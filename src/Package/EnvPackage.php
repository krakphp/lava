<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Krak\Cargo;
use Dotenv;

class EnvPackage extends Lava\AbstractPackage
{
    private $overload;

    public function __construct($overload = false) {
        $this->overload = $overload;
    }

    public function bootstrap(Lava\App $app) {
        try {
            $dotenv = new Dotenv\Dotenv($app->basePath());
            if ($this->overload) {
                $dotenv->overload();
            } else {
                $dotenv->load();
            }
        } catch (\Exception $e) {

        }

        $app['debug'] = (bool) getenv('APP_DEBUG') ?: false;
    }
}
