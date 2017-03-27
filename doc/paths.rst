=====
Paths
=====

The Lava Application also manages paths for your application.

Adding Paths
------------

You can add paths via the ``addPath`` function.

.. code-block:: php

    <?php

    $app->addPath('config', $app->basePath('config'));
    // you can overwrite an existing path by adding it again
    $app->addPath('config', __DIR__ . '/../config');

When initializing the Application, if you pass in the base path as the first argument to the constructor, the application will automatically add the ``base`` path.

.. code-block:: php

    $app = new Lava\App();
    assert(!$app->hasPath('base'));
    $app = new Lava\App(__DIR__);
    assert($app->hasPath('base'));

When a base path is provided, we also will default few other common paths in the application:

resources
    {base}/resources
views
    {base}/resources/views
logs
    {base}/var/log
config
    {base}/config

Building Paths
--------------

Once paths have been added to the application you can easily build paths with them.

.. code-block:: php

    <?php

    $app->path('base'); // returns the base path

    // these are aliases of $app->path($name);
    $app->basePath();
    $app->resourcesPath();
    $app->viewsPath();
    $app->logsPath();
    $app->configPath();

    // you can then build paths from stored paths as well
    $app->path('views', 'layouts/home.twig'); // returns a path like {base}/resources/views/layouts/home.twig

    // any of the aliases also accept an argument for appending
    $app->basePath('cache'); // returns {base}/cache

    $app->configPath(['doctrine', 'mapping', 'Entity.yml']); // will automatically join the array with the directory separator.
