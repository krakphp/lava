<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Krak\Cargo;
use InvalidArgumentException;

class RegisterPackagesPackage implements Lava\Bootstrap
{
    public function bootstrap(Lava\App $app) {
        foreach ($app['packages'] as $package) {
            $match = false;
            if ($package instanceof Lava\Bootstrap) {
                $match = true;
            }
            if ($package instanceof Cargo\ServiceProvider) {
                Cargo\register($app, $package);
                $match = true;
            }
            if ($package instanceof Lava\Package) {
                $package->with($app);
                $match = true;
            }

            if (!$match) {
                $type = Lava\Util\typeToString($package);
                throw new InvalidArgumentException("Packages must be an instance of Krak\Lava\{Package, Bootstrap} or Krak\Cargo\ServiceProvider. $type was given");
            }
        }
    }
}
