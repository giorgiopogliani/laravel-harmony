<?php

declare(strict_types=1);

namespace Performing\Harmony;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Performing\Harmony\Commands\HarmonyCommand;

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
}
