<?php

namespace Performing\Harmony;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Performing\Harmony\Commands\HarmonyCommand;

class HarmonyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('harmony')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_harmony_table')
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
