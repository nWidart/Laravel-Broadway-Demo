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
        $this->app->bind('Modules\Parts\Repositories\ReadModelPartRepository', function ($app) {
            $serializer = $app['Broadway\Serializer\SerializerInterface'];
            return new ElasticSearchReadModelPartRepository($app['elastic-search'], $serializer);
        });
    }

    /**
     * Register the command handlers on the command bus
     */
    private function registerCommandSubscribers()
    {
        $this->app->singleton('broadway.command-subscribers', function() {
            return [
                PartCommandHandler::class => 'Modules\Parts\Repositories\EventStorePartRepository'
            ];
        });
    }

    /**
     * Register the event listeners on the event bus
     */
    private function registerEventSubscribers()
    {
        $this->app->singleton('broadway.event-subscribers', function() {
            return [
                PartsThatWereManufacturedProjector::class => 'Modules\Parts\Repositories\ReadModelPartRepository'
            ];
        });
    }
}
