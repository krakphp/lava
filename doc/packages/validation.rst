==================
Validation Package
==================

The validation package provides integration with the `Krak\\Validation <https://github.com/krakphp/validation/>`_ package.

Services
--------

Krak\\Validation\\Kernel
~~~~~~~~~~~~~~~~~~~~~~~~

:Summary: An instance of the Kernel
:alias: validation

Configuration
-------------

To configure the validation kernel, you simply just access the validation kernel from the container and call any methods for configuring.

.. code-block:: php

    $app = new Krak\Lava\App(__DIR__);
    $app->with(new Krak\Lava\Package\ValidationPackage());

    $app['validation']->with(new SomeValidationPackage());
    $app['validation']->context([
        'a' => 1,
    ]);
