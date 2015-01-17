<?php namespace Modules\Core;

use Broadway\CommandHandling\SimpleCommandBus;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\EventHandling\SimpleEventBus;
use Broadway\EventStore\DBALEventStore;
use Broadway\EventStore\InMemoryEventStore;
use Broadway\UuidGenerator\Rfc4122\Version4Generator;
use Illuminate\Support\ServiceProvider;
use Modules\Parts\Repositories\EventStorePartRepository;

class LaravelBroadwayServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Bind a CommandBus
        $this->app->bindShared('Broadway\CommandHandling\CommandBusInterface', function () {
            return new SimpleCommandBus();
        });

        // Bind a Uui Generator
        $this->app->bindShared('Broadway\UuidGenerator\UuidGeneratorInterface', function () {
            return new Version4Generator();
        });

        // Bind an event dispatcher
        $this->app->bindShared('Broadway\EventDispatcher\EventDispatcherInterface', function () {
            return new EventDispatcher();
        });

        $this->bindEventStorage();
    }

    private function bindEventStorage()
    {
        $this->app->bind('Broadway\EventStore\EventStoreInterface', function () {
            return new InMemoryEventStore(); # Temporary Needs Broadway\EventStore\DBALEventStore
        });

        $this->app->bind('Broadway\EventHandling\EventBusInterface', function () {
            new SimpleEventBus();
        });

        // Binding the Part Repository
        $this->app->bind('Modules\Parts\Repositories\PartRepository', function ($app) {
            $eventStore = $app['Broadway\EventStore\EventStoreInterface'];
            $eventBus = $app['Broadway\EventHandling\EventBusInterface'];
            return new EventStorePartRepository($eventStore, $eventBus);
        });
    }
}
