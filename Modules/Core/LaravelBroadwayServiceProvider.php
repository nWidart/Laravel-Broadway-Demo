<?php namespace Modules\Core;

use Broadway\CommandHandling\SimpleCommandBus;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\EventHandling\SimpleEventBus;
use Broadway\EventStore\DBALEventStore;
use Broadway\ReadModel\ElasticSearch\ElasticSearchRepository;
use Broadway\Serializer\SimpleInterfaceSerializer;
use Broadway\UuidGenerator\Rfc4122\Version4Generator;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Elasticsearch\Client;
use Illuminate\Support\ServiceProvider;

class LaravelBroadwayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->bindEventClasses();
        $this->bindCommandClasses();
        $this->bindSerializers();
        $this->bindEventStorage();
        $this->bindMiscClasses();
        $this->bindReadModelClasses();
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

    private function bindReadModelClasses()
    {
        $driver = $this->app['config']->get('broadway.read-model');
        $config = $this->app['config']->get("broadway.read-model-connections.{$driver}");

        $client = new Client($config['config']);

        $this->app->bind('Broadway\ReadModel\RepositoryInterface', function ($app) use ($client, $config) {
            $serializer = $app['Broadway\Serializer\SerializerInterface'];
            return new ElasticSearchRepository($client, $serializer, $config['index'], 'class');
        });
    }
}
