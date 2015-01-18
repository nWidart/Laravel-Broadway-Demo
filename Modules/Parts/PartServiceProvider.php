<?php namespace Modules\Parts;

use Elasticsearch\Client;
use Illuminate\Support\ServiceProvider;
use Modules\Parts\Commands\Handlers\PartCommandHandler;
use Modules\Parts\ReadModel\PartsThatWereManufacturedProjector;
use Modules\Parts\Repositories\ElasticSearchReadModelPartRepository;
use Modules\Parts\Repositories\MysqlEventStorePartRepository;

class PartServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->bindEventSourcedRepositories();
        $this->bindReadModelRepositories();

        $this->registerCommandSubscribers();
        $this->registerEventSubscribers();
    }

    /**
     * Bind repositories
     */
    private function bindEventSourcedRepositories()
    {
        $this->app->bind('Modules\Parts\Repositories\EventStorePartRepository', function ($app) {
            $eventStore = $app['Broadway\EventStore\EventStoreInterface'];
            $eventBus = $app['Broadway\EventHandling\EventBusInterface'];
            return new MysqlEventStorePartRepository($eventStore, $eventBus);
        });
    }

    /**
     * Bind the read model repositories in the IoC container
     */
    private function bindReadModelRepositories()
    {
        $driver = $this->app['config']->get('broadway.read-model');
        $config = $this->app['config']->get("broadway.read-model-connections.{$driver}.config");

        $client = new Client($config);

        $this->app->bind('Modules\Parts\Repositories\ReadModelPartRepository', function ($app) use ($client, $config) {
            $serializer = $app['Broadway\Serializer\SerializerInterface'];
            return new ElasticSearchReadModelPartRepository($client, $serializer);
        });
    }

    /**
     * Register the command handlers on the command bus
     */
    private function registerCommandSubscribers()
    {
        $esPartRepository = $this->app['Modules\Parts\Repositories\EventStorePartRepository'];
        $handler = new PartCommandHandler($esPartRepository);
        $this->app['Broadway\CommandHandling\CommandBusInterface']->subscribe($handler);
    }

    /**
     * Register the event listeners on the event bus
     */
    private function registerEventSubscribers()
    {
        $repository = $this->app['Modules\Parts\Repositories\ReadModelPartRepository'];
        $partsThatWereManufacturedProjector = new PartsThatWereManufacturedProjector($repository);
        $this->app['Broadway\EventHandling\EventBusInterface']->subscribe($partsThatWereManufacturedProjector);
    }
}
