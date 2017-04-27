Welcome to Lava's documentation!
===================================

Middlewares written for http applications

Installation
~~~~~~~~~~~~

Install via composer at `krak/lava`

Basic Usage
~~~~~~~~~~~

.. code-block:: php

    <?php

    use Krak\Lava;

    $app = new Lava\App();
    $app->routes(function($r) {
        $r->get('/hello/{name}', function($name) {
            return "Hello $name!";
        });
    });

    $app->serve();

More documentation coming soon! For now look over the examples and source code for information.

.. toctree::
   :maxdepth: 2

   app
   paths
   stacks
   errors
   console
   packages
   events
   invoke-action
   marshal-response
   response-factory
   web-server-integration
