<?php

namespace Tests;

use Inertia\Inertia;
use Inertia\ServiceProvider as InertiaServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Performing\Harmony\HarmonyServiceProvider;
use Tests\App\Models\User;

class TestCase extends Orchestra
{
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', 'Tests\App\Http\Kernel');
    }

    protected function getPackageProviders($app)
    {
        return [
            HarmonyServiceProvider::class,
            InertiaServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        Inertia::setRootView('harmony::app');

        config()->set('auth.providers.0.model', User::class);
        config()->set('auth.providers.0.guard', 'sanctum');
        config()->set('inertia.testing.page_paths', [ __DIR__ . '/../resources/pages' ]);
    }
}
