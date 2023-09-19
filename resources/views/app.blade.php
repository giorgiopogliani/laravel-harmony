<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @php
        $componentName = str_replace('harmony::', '', $page['component']);
        $componentPath = str_starts_with($page['component'], 'harmony::')
            ? "vendor/performing/harmony/resources/pages/{$componentName}.vue"
            : "resources/pages/{$page['component']}.vue"
        @endphp
        @vite(['resources/app.ts', $componentPath])
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        @inertia
    </body>
</html>
