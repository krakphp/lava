<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Psr\Log\LoggerInterface;
use Monolog;

class MonologPackage implements Lava\Package
{
    public function with(Lava\App $app) {
        $app[Monolog\Handler\HandlerInterface::class] = function($app) {
            $filename = $app->logsPath($app['monolog.log_file_name']);
            if ($app['monolog.rotate_log_files']) {
                return new Monolog\Handler\RotatingFileHandler($filename);
            } else {
                return new Monolog\Handler\StreamHandler($filename);
            }
        };
        $app[Monolog\Formatter\FormatterInterface::class] = function($app) {
            return new Monolog\Formatter\LineFormatter(null, null, true, true);
        };
        $app[Monolog\Logger::class] = function($app) {
            $logger = new Monolog\Logger($app['name']);
            $handler = $app[Monolog\Handler\HandlerInterface::class];
            $handler->setFormatter($app[Monolog\Formatter\FormatterInterface::class]);
            $logger->pushHandler($handler);
            $logger->pushProcessor(new Monolog\Processor\PsrLogMessageProcessor());
            return $logger;
        };
        $app->wrap(LoggerInterface::class, function($logger, $app) {
            return $app[Monolog\Logger::class];
        });

        $app['monolog.rotate_log_files'] = false;
        $app['monolog.log_file_name'] = 'app.log';
    }
}
