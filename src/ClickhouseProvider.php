<?php

namespace Serkarn\ClickhouseMigrations;

class ClickhouseProvider extends \Illuminate\Support\ServiceProvider
{
    
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\MigrationCreate::class,
                Console\Migrate::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('clickhouse', function ($app) {
            return new Clickhouse();
        });
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return 'serkarn-clickhouse';
    }
    
}

