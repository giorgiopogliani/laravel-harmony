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
