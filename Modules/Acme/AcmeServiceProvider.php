<?php namespace Modules\Acme;

use Illuminate\Support\ServiceProvider;

class AcmeServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        include_once __DIR__.'/Http/routes.php';
    }
}
