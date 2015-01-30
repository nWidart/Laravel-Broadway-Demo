<?php namespace Modules\Acme\Controllers;

use Broadway\CommandHandling\SimpleCommandBus;
use Broadway\Domain\DomainEventStream;
use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\EventHandling\SimpleEventBus;
use Modules\Acme\Commands\ExampleCommand;
use Modules\Acme\Commands\ExampleCommandHandler;
use Modules\Acme\Listeners\MyEventListener;
use stdClass;

class HomeController extends \BaseController
{
    public function __construct()
    {
    }

    public function index()
    {
        // Setup the command handler
        $commandHandler = new ExampleCommandHandler();

        // Create a command bus and subscribe the command handler at the command bus
        $commandBus = new SimpleCommandBus();
        $commandBus->subscribe($commandHandler);

        // Create and dispatch the command!
        $command = new ExampleCommand('Hi from command!');
        $commandBus->dispatch($command);
    }

    public function eventDispatcher()
    {
        $eventDispatcher = new EventDispatcher();

        $eventDispatcher->addListener('my_event', function ($arg1, $arg2) {
            echo "Arg1: $arg1\n";
            echo "Arg2: $arg2\n";
        });

        // Dispatch with an array of arguments
        $eventDispatcher->dispatch('my_event', ['one', 'two']);
    }

    public function eventHandling()
    {
        // Create the event bus and subscribe the created event listener
        $eventBus = new SimpleEventBus();
        $eventListener = new MyEventListener();
        $eventBus->subscribe($eventListener);

        // Create a domain event stream to publish
        $metadata = new Metadata(['source' => 'example']);
        $domainMessage = DomainMessage::recordNow(42, 1, $metadata, new stdClass());
        $domainEventStream = new DomainEventStream([$domainMessage]);

        // Publish the message, and get output from the event handler \o/
        $eventBus->publish($domainEventStream);
    }
}
