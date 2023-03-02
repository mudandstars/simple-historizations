<?php

namespace Mudandstars\SimpleHistorizations;

use Mudandstars\SimpleHistorizations\Commands\MakeHistorizationFilesCommand;
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
