<?php

namespace Krak\Lava\Console;

use Krak\Lava;
use Krak\Cargo;
use Krak\AutoArgs;
use Symfony\Component\Console;

class Command extends Console\Command\Command
{
    protected $lava;
    protected $input;
    protected $output;

    private $io;

    public function setLava(Lava\App $lava) {
        $this->lava = $lava;
    }

    public function getApp() {
        return $this->lava;
    }

    public function io() {
        if ($this->io) {
            return $this->io;
        }
        $this->io = new Console\Style\SymfonyStyle($this->input, $this->output);
        return $this->io;
    }

    protected function configure() {
        if (!method_exists($this, 'define')) {
            throw new \LogicException('Command does not contain the define method');
        }

        $this->define(new CommandDefinitionWrapper($this));
    }

    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output) {
        $this->input = $input;
        $this->output = $output;

        if (!method_exists($this, 'handle')) {
            throw new \LogicException('Command does not contain the handle method');
        }

        $aa = $this->lava[AutoArgs\AutoArgs::class];
        return $aa->invoke([$this, 'handle'], [
            'objects' => [$this->lava, $this->input, $this->output],
            'container' => Cargo\toInterop($this->lava),
        ]);
    }

    public function input() {
        return $this->input;
    }

    public function argument($name) {
        return $this->input->getArgument($name);
    }
    public function option($name) {
        return $this->input->getOption($name);
    }

    public function output() {
        return $this->output;
    }

    public function writeln(...$args) {
        return $this->output->writeln(...$args);
    }
    public function write(...$args) {
        return $this->output->write(...$args);
    }
    public function writeStyled($style, $message) {
        if (is_string($message)) {
            $message = [$message];
        }

        $message = array_map(function($message) use ($style) {
            return sprintf("<%s>%s</%s>", $style, $message, $style);
        }, $message);

        return $this->writeln($message);
    }

    public function info($message) {
        return $this->writeStyled('info', $message);
    }
    public function comment($message) {
        return $this->writeStyled('comment', $message);
    }
    public function question($message) {
        return $this->writeStyled('question', $message);
    }
    public function error($message) {
        return $this->writeStyled('error', $message);
    }
}
