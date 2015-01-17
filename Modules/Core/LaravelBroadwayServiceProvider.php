<?php namespace Modules\Core;

use Broadway\CommandHandling\SimpleCommandBus;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\EventHandling\SimpleEventBus;
use Broadway\EventStore\DBALEventStore;
use Broadway\Serializer\SimpleInterfaceSerializer;
use Broadway\UuidGenerator\Rfc4122\Version4Generator;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Illuminate\Support\ServiceProvider;
use Modules\Parts\Repositories\EventStorePartRepository;

class LaravelBroadwayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->bindEventClasses();
        $this->bindCommandClasses();
        $this->bindSerializers();
        $this->bindEventStorage();
        $this->bindMiscClasses();
        $this->bindEventSourcedRepositories();
    }

    /**
     * Bind all classes event related classes
     */
    private function bindEventClasses()
    {
        $this->app->bind('Broadway\EventDispatcher\EventDispatcherInterface', function () {
            return new EventDispatcher();
        });

        $this->app->bind('Broadway\EventHandling\EventBusInterface', function () {
            return new SimpleEventBus();
        });
    }

    /**
     * Bind all command related classes
     */
    private function bindCommandClasses()
    {
        $this->app->bind('Broadway\CommandHandling\CommandBusInterface', function () {
            return new SimpleCommandBus();
        });
    }

    /**
     * Bind the Serializer
     */
    private function bindSerializers()
    {
        $this->app->bind('Broadway\Serializer\SerializerInterface', function () {
            return new SimpleInterfaceSerializer();
        });
    }

    /**
     * Bind the event storage class
     */
    private function bindEventStorage()
    {
        $connectionParams = $this->getStorageConnectionParameters();
        $this->app->bind('Broadway\EventStore\EventStoreInterface', function ($app) use ($connectionParams) {
            $configuration = new Configuration();

            $connection = DriverManager::getConnection($connectionParams, $configuration);
            $payloadSerializer = $app['Broadway\Serializer\SerializerInterface'];
            $metadataSerializer = $app['Broadway\Serializer\SerializerInterface'];
            return new DBALEventStore($connection, $payloadSerializer, $metadataSerializer, 'event_store');
        });
    }

    /**
     * Bind miscellaneous helper classes
     */
    private function bindMiscClasses()
    {
        // Bind a Uui Generator
        $this->app->bind('Broadway\UuidGenerator\UuidGeneratorInterface', function () {
            return new Version4Generator();
        });
    }

    /**
     * Bind repositories (should be a separate SP)
     */
    private function bindEventSourcedRepositories()
    {
        // Binding the Part Repository
        $this->app->bind('Modules\Parts\Repositories\PartRepository', function ($app) {
            $eventStore = $app['Broadway\EventStore\EventStoreInterface'];
            $eventBus = $app['Broadway\EventHandling\EventBusInterface'];
            return new EventStorePartRepository($eventStore, $eventBus);
        });
    }

    /**
     * Make a connection parameters array based on the laravel configuration
     * @return array
     */
    private function getStorageConnectionParameters()
    {
        $driver = $this->app['config']->get('database.default');
        $connectionParams = $this->app['config']->get("database.connections.{$driver}");

        $connectionParams['dbname'] = $connectionParams['database'];
        $connectionParams['user'] = $connectionParams['username'];
        unset($connectionParams['database'], $connectionParams['username']);
        $connectionParams['driver'] = "pdo_$driver";

        return $connectionParams;
    }
}
