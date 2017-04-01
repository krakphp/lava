======
Stacks
======

Stacks are used to manage the different components of the Lava App. Stacks hold handlers that can be pushed on which easily allow decoration and cusomtization.

Stacks are managed by the following functions within the App:

:hasStack($name):
    Determines whether or not a stack exists
:stack($name):
    Returns the given stored stack
:addStack($name, array $entries = []):
    Adds a new stack into the app at the given name. It will replace an existing stack with the same name.

The ``LavaPackage`` defines several stacks by default: http, routes, renderError, marshalResponse, invokeAction. Each of these default stacks have their own accessor methods: ``httpStack``, ``renderErrorStack``, ``marshalResponseStack``, etc....

Each of these stacks have their own purpose and manage a different set of handlers.

Handlers can either be callables or classes with the method `handle`. For example, a render error handler could be one of the following:

.. code-block:: php

    <?php

    function customRenderError() {
        return function($error, $request, $next) {
            //
        };
    }

    // or

    class CustomRenderError
    {
        public function handle($error, $request, $next) {
            //
        }
    }

    // each of these would work for registering a handler.
    $app->renderErrorStack()
        ->push(customRenderError())
        ->push(CustomRenderError::class) // this will be created from the service container when it's invoked
        ->push(new CustomRenderError());

The class based approach is great if you want to register your handler in the service container to lazy load dependencies.
