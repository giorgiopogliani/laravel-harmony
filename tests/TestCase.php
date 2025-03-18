<?php

declare(strict_types=1);

namespace Tests;

use Inertia\Inertia;
use Inertia\ServiceProvider as InertiaServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Performing\Harmony\HarmonyServiceProvider;
use Tests\App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use Laracasts\Flash\FlashServiceProvider;
use Performing\Harmony\Facades\Harmony;
use Tests\App\Components\CounterComponent;
use Tighten\Ziggy\ZiggyServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );

        $this->loadLaravelMigrations();

        Schema::create('posts', function ($table) {
            $table->id();
            $table->string('title');
            $table->string('body');
            $table->timestamps();
        });

        $this->artisan('migrate');

        Harmony::registerRoute('counter', CounterComponent::class);
    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', 'Tests\App\Http\Kernel');
    }

    protected function getPackageProviders($app)
    {
        return [
            FlashServiceProvider::class,
            HarmonyServiceProvider::class,
            ZiggyServiceProvider::class,
            InertiaServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        Inertia::setRootView('harmony::app');

        config()->set('auth.providers.0.model', User::class);
        config()->set('auth.providers.0.guard', 'sanctum');
        config()->set('inertia.testing.page_paths', [__DIR__ . '/../resources/pages']);
    }
}
