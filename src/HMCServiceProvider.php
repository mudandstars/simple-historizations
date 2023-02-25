<?php

namespace Mudandstars\HistorizeModelChanges;

use Mudandstars\HistorizeModelChanges\Commands\MakeHistorizationFilesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HMCServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('historize-model-changes')
            ->hasCommand(MakeHistorizationFilesCommand::class);
    }
}
