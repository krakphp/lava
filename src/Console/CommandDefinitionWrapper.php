<?php

namespace Krak\Lava\Console;

use Symfony\Component\Console;

/** wrapper for defining commands */
class CommandDefinitionWrapper
{
    private $command;

    public function __construct(Console\Command\Command $command) {
        $this->command = $command;
    }

    public function name($name) {
        $this->command->setName($name);
        return $this;
    }

    public function description($description) {
        $this->command->setDescription($description);
        return $this;
    }

    public function help($help) {
        $this->command->setHelp($help);
        return $this;
    }

    public function argument($name, $description = '', $type = Console\Input\InputArgument::REQUIRED) {
        $this->command->addArgument($name, $type, $description);
        return $this;
    }
    public function requiredArgument($name, $description = '') {
        return $this->argument($name, $description, Console\Input\InputArgument::REQUIRED);
    }
    public function optionalArgument($name, $description = '') {
        return $this->argument($name, $description, Console\Input\InputArgument::OPTIONAL);
    }
    public function arrayArgument($name, $description = '') {
        return $this->argument($name, $description, Console\Input\InputArgument::IS_ARRAY);
    }
    public function optionalArrayArgument($name, $description = '') {
        return $this->argument($name, $description, Console\Input\InputArgument::OPTIONAL | Console\Input\InputArgument::IS_ARRAY);
    }
    public function requiredArrayArgument($name, $description = '') {
        return $this->argument($name, $description, Console\Input\InputArgument::REQUIRED | Console\Input\InputArgument::IS_ARRAY);
    }

    public function option($name, $alias = null, $description = '', $type = Console\Input\InputOption::VALUE_NONE) {
        $this->command->addOption($name, $alias, $type, $description);
        return $this;
    }
    public function emptyOption($name, $alias = null, $description = '') {
        return $this->option($name, $alias, $description, Console\Input\InputOption::VALUE_NONE);
    }
    public function arrayOption($name, $alias = null, $description = '') {
        return $this->option($name, $alias, $description, Console\Input\InputOption::VALUE_IS_ARRAY);
    }
    public function valueOption($name, $alias = null, $description = '') {
        return $this->option($name, $alias, $description, Console\Input\InputOption::VALUE_REQUIRED);
    }
    public function optionalValueOption($name, $alias = null, $description = '') {
        return $this->option($name, $alias, $description, Console\Input\InputOption::VALUE_OPTIONAL);
    }
    public function requiredArrayOption($name, $alias = null, $description = '') {
        return $this->option($name, $alias, $description, Console\Input\InputOption::VALUE_IS_ARRAY | Console\Input\InputOption::VALUE_REQUIRED);
    }
    public function optionalArrayOption($name, $alias = null, $description = '') {
        return $this->option($name, $alias, $description, Console\Input\InputOption::VALUE_IS_ARRAY | Console\Input\InputOption::VALUE_OPTIONAL);
    }

    public function getCommand() {
        return $this->command;
    }
}
