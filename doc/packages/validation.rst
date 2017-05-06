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

Middleware
----------

ConvertViolationExceptionMiddleware
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This middleware will convert catch any thrown ``ViolationException`` and will convert into an error response like ``Controller::validate`` will.

.. code-block:: php

    <?php

    // in some service function
    $validator = $this->validation->make('string')
    $violation = $validator->validate(1);

    if ($violation) {
        $violation->abort()
    }

This is very useful if you want to add more domain validation in your model layer.
