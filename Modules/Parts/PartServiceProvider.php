<?php namespace Modules\Parts;

use Doctrine\DBAL\Connection;
use Illuminate\Support\ServiceProvider;
use Modules\Parts\Commands\Handlers\PartCommandHandler;
use Modules\Parts\Console\ReplayPartsCommand;
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

        $this->registerConsoleCommands();

        include_once __DIR__.'/Http/routes.php';
    }

    public function boot()
    {
        $this->app['view']->addNamespace('parts', __DIR__.'/resources/views');
    }

    /**
     * Bind repositories
     */
    private function bindEventSourcedRepositories()
    {
        $this->app->bind(\Modules\Parts\Repositories\EventStorePartRepository::class, function ($app) {
            $eventStore = $app[\Broadway\EventStore\EventStoreInterface::class];
            $eventBus = $app[\Broadway\EventHandling\EventBusInterface::class];

            return new MysqlEventStorePartRepository($eventStore, $eventBus, $app[Connection::class]);
        });
    }

    /**
     * Bind the read model repositories in the IoC container
     */
    private function bindReadModelRepositories()
    {
        $this->app->bind(\Modules\Parts\Repositories\ReadModelPartRepository::class, function ($app) {
            $serializer = $app[\Broadway\Serializer\SerializerInterface::class];

            return new ElasticSearchReadModelPartRepository($app['Elasticsearch'], $serializer);
        });
    }

    /**
     * Register the command handlers on the command bus
     */
    private function registerCommandSubscribers()
    {
        $partCommandHandler = new PartCommandHandler($this->app[\Modules\Parts\Repositories\EventStorePartRepository::class]);

        $this->app['laravelbroadway.command.registry']->subscribe([
            $partCommandHandler
        ]);
    }

    /**
     * Register the event listeners on the event bus
     */
    private function registerEventSubscribers()
    {
        $partsThatWereManfacturedProjector = new PartsThatWereManufacturedProjector($this->app[\Modules\Parts\Repositories\ReadModelPartRepository::class]);

        $this->app['laravelbroadway.event.registry']->subscribe([
            $partsThatWereManfacturedProjector
        ]);
    }

    private function registerConsoleCommands()
    {
        $this->app->singleton('command.asgard.replay.parts', function ($app) {
            $eventStorePartRepository = $app[\Modules\Parts\Repositories\EventStorePartRepository::class];
            $eventBus = $app[\Broadway\EventHandling\EventBusInterface::class];
            return new ReplayPartsCommand($eventStorePartRepository, $eventBus);
        });
        $this->commands([
            'command.asgard.replay.parts',
        ]);
    }
}
