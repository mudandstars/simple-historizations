<?php

namespace Mudandstars\HistorizeModelChanges;

use Illuminate\Support\ServiceProvider;
use Mudandstars\HistorizeModelChanges\Traits\HistorizeModelChange;

class ASTServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->(HistorizeModelChange::class);
    }
}
