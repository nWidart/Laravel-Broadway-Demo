<?php namespace Modules\Core;

use Broadway\CommandHandling\SimpleCommandBus;
use Broadway\Domain\DomainEventStream;
use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\EventHandling\SimpleEventBus;
use Broadway\EventStore\DBALEventStore;
use Broadway\Serializer\SimpleInterfaceSerializer;
use Broadway\UuidGenerator\Rfc4122\Version4Generator;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Illuminate\Support\ServiceProvider;
use stdClass;

class LaravelBroadwayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->bindEventClasses();
        $this->bindCommandClasses();
        $this->bindSerializers();
        $this->bindEventStorage();
        $this->bindMiscClasses();
    }

    /**
     * Bind all classes event related classes
     */
    private function bindEventClasses()
    {
        $this->app->singleton('Broadway\EventDispatcher\EventDispatcherInterface', function () {
            return new EventDispatcher();
        });

        $this->app->singleton('Broadway\EventHandling\EventBusInterface', function () {
            return new SimpleEventBus();
        });

        // Testing ...
        $metadata = new Metadata(['source' => 'example']);
        $domainMessage = DomainMessage::recordNow(1, 0, $metadata, new stdClass());
        $domainEventStream = new DomainEventStream([$domainMessage]);
        $this->app['Broadway\EventHandling\EventBusInterface']->publish($domainEventStream);
    }

    /**
     * Bind all command related classes
     */
    private function bindCommandClasses()
    {
        $this->app->singleton('Broadway\CommandHandling\CommandBusInterface', function () {
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
