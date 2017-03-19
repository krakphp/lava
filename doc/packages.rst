Packages
========

Packages offer functionality to the Lava App. Even the core of the Lava system is defined in a package!

Please have a look at the included packages:

- :doc:`packages/lava`
- :doc:`packages/plates`
- :doc:`packages/rest`


Registering Packages
====================

You register packages into your app via the ``with`` method.

.. code-block:: php

    // register one package
    $app->with(new Package());
    // register multiple packages
    $app->with([
        new FooPackage(),
        new BarPackage(),
    ]);
    // register a package as anonymous function
    $app->with(function($app) {
        // configure
    });
    // register a Cargo Service provider
    $app->with(new FooServiceProvider());
    // register a Bootstrapper
    $app->with(new FooBootstrap());

Creating Packages
=================

In this example, we are creating a package that registers services, bootstraps the app, and configured the app. A package doesn't need to implement all three interfaces for it to be registerd as package.
.. code-block:: php

    use Krak\Lava;
    use Krak\Cargo;

    class AcmePackage implements Lava\Bootstrap, Lava\Package, Cargo\ServiceProvider
    {
        public function boostrap(Lava\App $app) {
            // these will be invoked on bootstrap
            // any initialization to the system should be done here, globals, filesystem, etc...
        }

        public function with(Lava\App $app) {
            // these are invoked after the app is bootstraped and services have been
            // registered. You can configure the application, add events, register more
            // services, add global or route middleware, define routes, add commands, etc...
        }

        public function register(Cargo\Container $c) {
            // register services on the Application Container. These are invoked
            // the moment they are registered on the app instance.
        }
    }

We also provide an ``AbstractPackage`` class which is a convenience so that you don't have to implement three separate interfaces, and you can optionally define any of the methods shown in the previous example.

.. code-block:: php

    class AcmePackage extends Krak\Lava\AbstractPackage
    {

    }
