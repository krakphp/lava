=======
Console
=======

The Lava App integrates very nicely with the Symfony Console component providing excellent console applications.

Creating Commands
-----------------

Creating commands is done by simply extending the Lava Console Command. This just adds some niceties over the Symony Console Command like automatic injection and fluent interfaces for defining your command.

.. code-block:: php

    <?php

    use Krak\Lava\Console\Command;

    class FooCommand extends Command
    {
        public function define($def) {
            $def->name('acme:foo')
                ->description('The foo command')
                ->requiredArgument('bar', 'Bar argument')
                ->valueOption('debug', 'd', 'Perform in debug mode');
        }

        /** supports automatic injection */
        public function handle(AcmeService $service) {
            $bar = $this->argument('bar');
            $debug = $this->option('debug');

            $this->info("Hello!");
        }
    }

In addition to creating a command class, you can just define a Closure command via the app ``addCommand`` method.

.. code-block:: php

    <?php

    $app->addCommand('acme:foo', function(FooService $service) {
        $bar = $this->argument('bar');
        $this->info("Hi!");
    })->description("Foor Command")->requiredArgument('bar', 'Bar argument');

This will create the command and add it to the application commands.

Registering Commands
--------------------

You can register any Lava or Symfony Console commands via the ``commands`` method.

.. code-block:: php

    <?php

    $app->commands([
        Acme\Console\FooCommand::class, // string of class name (will be loaded from service container)
        new Acme\Console\BarCommand(), // actual command instance
        new Some\Symfony\ConsoleCommand()
    ]);

Running the Console
-------------------

To run the console application you use the ``runConsole`` method.

.. code-block:: php

    <?php

    $app->runConsole();

This will create the console app, register commands, run the console, and exit with the proper status code.

Configuring Commands
--------------------

Whether you are creating a command via ``addCommand`` or by extending the Lava Console Command, configuration is done from the ``CommandDefinitionWrapper`` instance. This class provides a fluent interface for configuring a symfony console command.

Here is the available API:

.. code-block:: php

    <?php

    $def
        ->name('Command Name')
        ->description('Command description...')
        ->help('Command Help Text')
        ->argument('name', 'description', $type = Symfony\Component\Console\Input\InputArgument::REQUIRED)
        ->requiredArgument('name', 'description')
        ->optionalArgument('name', 'description')
        ->arrayArgument('name', 'description')
        ->optionalArrayArgument('name', 'description')
        ->requiredArrayArgument('name', 'description')
        ->option('option-name', $alias = 'o', 'Option description', $type = Symfony\Component\Console\Input\InputOption::VALUE_NONE)
        ->emptyOption('option-name', $alias = 'o', 'Option Description')
        ->arrayOption('option-name', $alias = 'o', 'Option Description')
        ->valueOption('option-name', $alias = 'o', 'Option Description')
        ->optionalValueOption('option-name', $alias = 'o', 'Option Description')
        ->requiredArrayOption('option-name', $alias = 'o', 'Option Description')
        ->optionalArrayOption('option-name', $alias = 'o', 'Option Description');

    $def->getCommand(); // returns the symfony command isntance

Command Input
-------------

You can retrieve command input a few ways:

.. code-block:: php

    public function handle(Symfony\Component\Console\Input\InputInterface $input /* type hinting */) {
        $input = $this->input(); // returns symfony input
        $argument = $this->argument('argument-name');
        $option = $this->option('option-name');
    }

Command Output
--------------

.. code-block:: php

    public function handle(Symfony\Component\Console\Output\OutputInterface $output /* type hinting */) {
        $output = $this->output(); // returns symfony output
        $this->writeln('foo');
        $this->info('bar'); // alias of $this->output()->writeln("<info>bar</info>");
        // also supports: $this->comment(), $this->error(), $this->question()
        $io = $this->io(); // returns instance of symfony console style.
        $io->success("Great!");
    }

Extending the Console
---------------------

.. code-block:: php

$app->wrap('console', function($console) {
    $console->mergeHelperSets(new CustomHelperSet());
    return $console;
});
