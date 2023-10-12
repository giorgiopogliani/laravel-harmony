<?php

namespace Performing\Harmony\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Performing\Harmony\Concerns\HasProps;
use Performing\Harmony\Prop;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    use HasProps;

    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'harmony::app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), $this->getProps());
    }

    #[Prop]
    public function ziggy()
    {
        return array_merge((new Ziggy())->toArray(), [
            'location' => request()->url(),
        ]);
    }

    #[Prop]
    public function flash()
    {
        return fn () => session('flash_notification') ?? [];
    }

    #[Prop]
    public function auth()
    {
        return [
            'user' => auth()->user()
        ];
    }

    #[Prop]
    public function locale()
    {
        return fn () => app()->getLocale();
    }
}
