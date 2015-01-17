<?php namespace Modules\Core;

use Broadway\CommandHandling\SimpleCommandBus;
use Broadway\EventDispatcher\EventDispatcher;
use Broadway\UuidGenerator\Rfc4122\Version4Generator;
use Illuminate\Support\ServiceProvider;

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
    }
}
