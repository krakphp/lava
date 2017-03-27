==============
Plates Package
==============

The plates package provides tight integration with the League\\Plates framework to provide simple template engine integration and usage.

Parameters
----------

plates.ext
~~~~~~~~~~

:Summary: Specifies the extension of template files to use
:Default: php

plates.views_path
~~~~~~~~~~~~~~~~~

:Summary: The base path to where the views are held.
:Default: ``{base_path}/views``

plates.error_paths
~~~~~~~~~~~~~~~~~~

:Summary: A map of http errors to files
:Default: ``[]``

.. code-block:: php

    <?php

    $app['plates.error_paths'] = [
        '404' => 'errors/404', // will only show on 404 errors
        'error' => 'errors/error' // default error page to show if no specific file is set
    ];

Services
--------

League\\Plates\\Engine
~~~~~~~~~~~~~~~~~~~~~~

:Summary: A configured instance of a plates engine.
:Alias: plates

If you want to perform any customization to the plates engine, just wrap the engine service via ``Krak\Cargo\wrap``

Stacks
------

PlatesPackage defines several stacks.

injectRequestIntoPlates
~~~~~~~~~~~~~~~~~~~~~~~

:Summary: Injects the request and application instance into the plates engine as global variables.
:Stack: http

From any template, you can access the ``$app`` and ``$request`` variables which will be instances of the ``Krak\Lava\App`` and ``Psr\Http\Message\\ServerRequestInterface``.

**Note:** Keep in mind the http requests are immutable, so any modifications done to the request down the line will not be reflected in the request instance passed to the plates engine.

platesMarshalResponse
~~~~~~~~~~~~~~~~~~~~~

:Summary: Marshals a two-tuple into a rendered plate template.
:Stack: marshal_response

You can return a two-tuple of ``(string, array)`` which represents the template path and data for the template.

.. code-block:: php

    <?php

    $routes->get('/', function() {
        return ['home', [
            'foo' => $bar,
        ]];
    });

platesRenderError
~~~~~~~~~~~~~~~~~

:Summary: Renders errors from user-defined templates
:Stack: render_error

If any of the ``plates.error_paths`` entries are set, then this renderer will try to load the data from the templates.

Each error template is passed an error variable named ``$error``

Example Template:

.. code-block:: php

    <h1><?=$error->message?></h1>
    <code><?=$error->code?></code>
