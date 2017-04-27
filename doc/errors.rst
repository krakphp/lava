======
Errors
======

Error handling is done via the Krak\\Lava\\Error class. All exceptions will be caught and converted into an error instance. There is a renderError stack which is responsible for transforming error classes into actual responses.

We also provide an ErrorException class which is an exception that wraps an Error.

.. code-block:: php

    <?php

    use Krak\Lava;

    // creates an error
    $error = Lava\error(500, 'code', 'Message', ['param' => 1]);

    // throws an error exception
    Lava\abort(500, 'code', 'Message', ['param' => 1]);

From inside of a controller, you can either return an error, or throw an exception and the system will handle it fine.

If you are inside of a middleware chain, you can't return an error because you have to return a http response. For those times, you can use the following:

.. code-block:: php

    function myMiddleware() {
        return function($req, $next) {
            return $next->abort(500, 'code', 'Message')->render($req);
        };
    }

This will automatically call the renderError stack to convert your aborted error into a response to return in your handler.

Rendering Errors
----------------

If you have an error instance and would like to manually convert it into a response, you can use the app renderError method to do so.

.. code-block:: php

    <?php

    $app = new Krak\Lava\App();
    $resp = $app->renderError(Krak\Lava\error());
