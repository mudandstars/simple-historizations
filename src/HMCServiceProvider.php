<?php

namespace Mudandstars\HistorizeModelChanges;

use Illuminate\Support\ServiceProvider;
use MakeHistorizationFiles;

class HMCServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands([
            MakeHistorizationFiles::class,
        ]);
    }

    public function boot(): void
    {
        //
    }
}
