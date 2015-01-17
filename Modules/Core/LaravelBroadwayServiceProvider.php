<?php namespace Modules\Core;

use Broadway\CommandHandling\SimpleCommandBus;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\EventHandling\SimpleEventBus;
use Broadway\EventStore\DBALEventStore;
use Broadway\EventStore\InMemoryEventStore;
use Broadway\UuidGenerator\Rfc4122\Version4Generator;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
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
        $this->bindEventClasses();
        $this->bindEventStorage();
        $this->bindEventSourcedRepositories();
        $this->bindMiscClasses();
    }

    private function bindEventStorage()
    {
        $this->app->bind('Broadway\EventStore\EventStoreInterface', function ($app) {
            $configuration = new Configuration();

            $driver = $app['config']->get('database.default');
            $connectionParams = $app['config']->get("database.connections.{$driver}");
            $connectionParams['dbname'] = $connectionParams['database'];
            $connectionParams['user'] = $connectionParams['username'];
            unset($connectionParams['database'], $connectionParams['username']);
            $connectionParams['driver'] = "pdo_$driver";

            $connection = DriverManager::getConnection($connectionParams, $configuration);
            //$dbalEventStore = new DBALEventStore($connection);
            return new InMemoryEventStore(); # Temporary Needs Broadway\EventStore\DBALEventStore
        });

        $this->app->bind('Broadway\EventHandling\EventBusInterface', function () {
            return new SimpleEventBus();
        });
    }

    private function bindEventSourcedRepositories()
    {
        // Binding the Part Repository
        $this->app->bind('Modules\Parts\Repositories\PartRepository', function ($app) {
            $eventStore = $app['Broadway\EventStore\EventStoreInterface'];
            $eventBus = $app['Broadway\EventHandling\EventBusInterface'];
            return new EventStorePartRepository($eventStore, $eventBus);
        });
    }

    private function bindEventClasses()
    {
        // Bind an event dispatcher
        $this->app->bind('Broadway\EventDispatcher\EventDispatcherInterface', function () {
            return new EventDispatcher();
        });

        // Bind a CommandBus
        $this->app->bind('Broadway\CommandHandling\CommandBusInterface', function () {
            return new SimpleCommandBus();
        });
    }

    private function bindMiscClasses()
    {
        // Bind a Uui Generator
        $this->app->bind('Broadway\UuidGenerator\UuidGeneratorInterface', function () {
            return new Version4Generator();
        });
    }
}
