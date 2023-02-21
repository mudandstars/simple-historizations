<?php

namespace Mudandstars\HistorizeModelChanges;

use Illuminate\Support\ServiceProvider;
use Mudandstars\HistorizeModelChanges\Commands\MakeHistorizationFiles;

class HMCServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeHistorizationFiles::class,
            ]);
        }
    }
}
