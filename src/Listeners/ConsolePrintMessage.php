<?php

namespace Helix\Lego\Listeners;

use Illuminate\Log\Events\MessageLogged;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class ConsolePrintMessage
{
    private $output;

    /**
     * Handle the event.
     *
     * @param  MessageLogged  $event
     * @return void
     */
    public function handle(MessageLogged $event)
    {
        if (app()->runningInConsole() && config('console-print-log.enabled')) {
            $this->output = new ConsoleOutput();

            $this->output->getFormatter()->setStyle($event->level, $this->defineOutputStyle($event->level));

            $this->output->writeln("Log ($event->level): <{$event->level}>{$event->message}</{$event->level}>");
        }
    }

    private function defineOutputStyle($level)
    {
        switch ($level) {
            case 'emergency':
                return new OutputFormatterStyle('yellow', 'red', ['bold', 'underscore', 'blink']);

            case 'alert':
                return new OutputFormatterStyle('yellow', 'red', ['bold']);

            case 'critical':
                return new OutputFormatterStyle('white', 'red', ['bold']);

            case 'error':
                return new OutputFormatterStyle('white', 'red');

            case 'warning':
                return new OutputFormatterStyle('yellow', null, ['bold']);

            case 'notice':
                return new OutputFormatterStyle('yellow');

            case 'info':
                return new OutputFormatterStyle('white');

            case 'debug':
                return new OutputFormatterStyle('blue');

            default:
                return new OutputFormatterStyle('white');
        }
    }
}
