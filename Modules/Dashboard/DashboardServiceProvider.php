<?php namespace Modules\Dashboard;

use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    public function register()
    {
        include_once __DIR__.'/Http/routes.php';
    }

    public function boot()
    {
        $this->app['view']->addNamespace('dashboard', __DIR__.'/resources/views');
    }
}
