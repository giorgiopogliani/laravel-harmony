# Harmony

Collection of classes and vue components to speed your development of InertiaJs applications. 

## Installation

You can install the package via composer:

```bash
composer require performing/laravel-harmony
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="harmony-config"
```

Install npm dependencies: 

```bash
npm install tailwindcss @headlessui/vue @popperjs/core @vueuse/core @zag-js/checkbox @zag-js/combobox @zag-js/popover @zag-js/toast @zag-js/vue
```

Update your vite configuraiton as follows: 
```js
import { defineConfig } from 'vite';
import path from 'node:path';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import Components from "unplugin-vue-components/vite";

export default defineConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '~': path.resolve(__dirname, 'vendor/performing/laravel-harmony/resources'),
        }
    },
    plugins: [
        Components({
            dirs: [
                // Add other paths before if you want to
                // ovverride components with same name
                'vendor/performing/laravel-harmony/resources/components',
            ],
            extensions: ["vue"],
        }),
        // ...
    ]
})
```

Update your tsconfig
```json
{
    "compilerOptions": {
        // ...
        "paths": {
            "~/*": ["./vendor/performing/laravel-harmony/resources/*"]
        }
    },
    "include": [
        "vendor/performing/laravel-harmony/resources/**/*"
    ]
}
```

## Usage

```php
use Performing\Harmony\Page;
use Performing\Harmony\Components\TableComponent;

class ModelController 
{
    public function index() : Inertia\Response
    {
        return [
            Page::make('Title')
                ->table(
                    TableComponent::make()
                        ->rows(Model::query())
                        ->columns([ ... ])
                        ->filters([ ... ])
                        ->selectable()
                )
                ->render('harmony::resources/index')
        ]
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
