<?php

declare(strict_types=1);

namespace Performing\Harmony;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Performing\Harmony\Facades\Harmony;
use Performing\Harmony\Harmony as HarmonyHarmony;

class HarmonyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'harmony');

        // if ($this->app->runningInConsole()) {
        //     $this->publishes([
        //         __DIR__.'/../config/config.php' => config_path('harmony.php'),
        //     ], 'config');
        // }
    }

    public function register()
    {
        // Automatically apply the package configuration
        // $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'harmony');

        // Register the main class to use with the facade
        $this->app->singleton('harmony', function () {
            return new HarmonyHarmony;
        });
    }
}
