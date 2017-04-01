<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class AcmeService {
    public function uppercase($name) {
        return strtoupper($name);
    }
}

class HelloCommand extends Krak\Lava\Console\Command {
    public function define($def) {
        $def->name('acme:hello')
            ->description('Says hello to someone')
            ->requiredArgument('name', 'The name to use')
            ->option('debug', 'd', 'Perform in debug mode');
    }

    /** automatic injection */
    public function handle(AcmeService $service) {
        $name = $this->argument('name');
        if ($this->option('debug')) {
            $this->writeln('Performing in debug mode');
        }
        $name = $service->uppercase($name);
        $this->info("Hello $name!");
    }
}

$app = new Krak\Lava\App();
$app->commands([HelloCommand::class]);
$app->addCommand('acme:goodbye', function() {
    $this->comment('Goodbye ' . $this->argument('name'));
})->description('Says goodbye to someone')->requiredArgument('name', 'The name to use');

$app->runConsole();
