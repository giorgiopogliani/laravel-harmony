<?php

namespace Performing\Harmony;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Performing\Harmony\Commands\HarmonyCommand;
use Performing\Harmony\Flash;

class HarmonyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('harmony')
            ->hasConfigFile()
            ->hasViews('harmony')
            ->hasCommand(HarmonyCommand::class)
            ->hasRoutes('web');
    }

    public function registeringPackage()
    {
        $this->app->singleton(Flash::class, function ($app) {
            return new Flash();
        });
    }
}
