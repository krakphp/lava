<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Psr\Log\LoggerInterface;
use Monolog;

class MonologPackage implements Lava\Package
{
    public function with(Lava\App $app) {
        $app[Monolog\Logger::class] = function($app) {
            $logger = new Monolog\Logger($app['name']);
            $logger->pushHandler(new Monolog\Handler\StreamHandler($app->path('log')));
            $logger->pushProcessor(new Monolog\Processor\PsrLogMessageProcessor());
            return $logger;
        };
        $app->wrap(LoggerInterface::class, function($logger, $app) {
            return $app[Monolog\Logger::class];
        });
    }
}