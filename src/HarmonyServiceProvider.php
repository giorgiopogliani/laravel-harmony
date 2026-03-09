<?php

declare(strict_types=1);

namespace Performing\Harmony;

use Illuminate\Support\ServiceProvider;

final class HarmonyServiceProvider extends ServiceProvider
{
    public function boot(): void {}

    #[\Override]
    public function register(): void {}
}
