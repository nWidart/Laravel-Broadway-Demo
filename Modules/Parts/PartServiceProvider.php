<?php namespace Modules\Parts;

use Elasticsearch\Client;
use Illuminate\Support\ServiceProvider;
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

        /** @var \Broadway\EventHandling\EventBusInterface $eventBus */
        $eventBus = $this->app['Broadway\EventHandling\EventBusInterface'];
        $partsThatWereManufacturedProjector = new PartsThatWereManufacturedProjector($this->app['Modules\Parts\Repositories\EventStorePartRepository']);
        $eventBus->subscribe($partsThatWereManufacturedProjector);
    }

    /**
     * Bind repositories (should be a separate SP)
     */
    private function bindEventSourcedRepositories()
    {
        // Binding the Part Repository
        $this->app->bind('Modules\Parts\Repositories\EventStorePartRepository', function ($app) {
            $eventStore = $app['Broadway\EventStore\EventStoreInterface'];
            $eventBus = $app['Broadway\EventHandling\EventBusInterface'];
            return new MysqlEventStorePartRepository($eventStore, $eventBus);
        });
    }

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
}
