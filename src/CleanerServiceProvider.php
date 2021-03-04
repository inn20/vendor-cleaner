<?php

namespace Inn20\VendorCleaner;

use Illuminate\Support\ServiceProvider;

class CleanerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\ClearUpCommand::class
            ]);
            $this->publishes([__DIR__.'/../config' => config_path()], 'vendor-cleaner');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
