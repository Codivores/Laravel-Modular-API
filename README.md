# Laravel Modular API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codivores/laravel-modular-api.svg?style=flat-square)](https://packagist.org/packages/codivores/laravel-modular-api)
[![Total Downloads](https://img.shields.io/packagist/dt/codivores/laravel-modular-api.svg?style=flat-square)](https://packagist.org/packages/codivores/laravel-modular-api)

Simplify the creation of Laravel REST APIs that adhere to the [JSON:API](https://jsonapi.org) specification. The
package organizes the code into standalone services, promoting a modular, maintainable, and easily testable
architecture.

### Key Features:

- **JSON:API Compliance:** Generate API responses that fully comply with the JSON:API specification, ensuring data
  consistency and interoperability (based on [timacdonald/json-api](https://github.com/timacdonald/json-api) package)
- **Autonomous Services:** Structure your business logic into independent services, facilitating code reuse and clear
  separation of concerns.
- **API Versioning:** Easily version your API to manage changes and ensure backward compatibility.
- **Sub-APIs:** Create multiple sub-APIs (e.g., `public`, `protected`, `private`, ...) to handle different access
  levels and use cases.
- **Localization:** Serve multi-language content by processing a request header that automatically sets the locale for
  the entire request.

### Version support

- **PHP:** `8.2`, `8.3`
- **Laravel:** `11.0`

## Installation

You can install the package via composer:

```bash
composer require codivores/laravel-modular-api
```

If you want to use Hashids (short unique string identifiers from numbers) for your resources, you can install the
required package via composer:

```bash
composer require hashids/hashids
```

If you want to customize the configuration, you can publish the config file:

```bash
php artisan vendor:publish --tag="modular-api-config"
```

This is the contents of the published config file:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | API configuration
    |--------------------------------------------------------------------------
    */

    'api' => [

        'routing' => [
            'url' => env('MODULAR_API_ROUTING_URL', env('APP_URL', 'http://localhost')),
            'url_prefix' => env('MODULAR_API_ROUTING_URL_PREFIX', '/'),
            'route_prefix' => env('MODULAR_API_ROUTING_ROUTE_PREFIX', 'api'),
            'enable_version_prefix' => env('MODULAR_API_ROUTING_ENABLE_VERSION_PREFIX', true),
            'enable_type_prefix' => env('MODULAR_API_ROUTING_ENABLE_TYPE_PREFIX', true),
        ],

        'resource' => [
            'custom_type_resolver' => env('MODULAR_API_RESOURCE_CUSTOM_TYPE_RESOLVER', false),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Web configuration
    |--------------------------------------------------------------------------
    */

    'web' => [

        'routing' => [
            'url' => env('MODULAR_API_WEB_ROUTING_URL', env('APP_URL', 'http://localhost')),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Modular code configuration
    |--------------------------------------------------------------------------
    */

    'services' => [

        'root_path' => env('MODULAR_API_SERVICES_ROOT_PATH', 'Services'),

    ],

    /*
    |--------------------------------------------------------------------------
    | Features configuration
    |--------------------------------------------------------------------------
    */

    'features' => [

        'rate_limiting' => [
            'enabled' => env('MODULAR_API_FEATURE_RATE_LIMITING_ENABLED', false),
            'attempts' => env('MODULAR_API_FEATURE_RATE_LIMITING_ATTEMPTS_PER_MIN', 30),
            'expires' => env('MODULAR_API_FEATURE_RATE_LIMITING_EXPIRES_IN_MIN', 1),
        ],

        'localization' => [
            'enabled' => env('MODULAR_API_FEATURE_LOCALIZATION_ENABLED', false),
            'request_header' => env('MODULAR_API_FEATURE_LOCALIZATION_REQUEST_HEADER', 'Accept-Language'),
            'locales' => env('MODULAR_API_FEATURE_LOCALIZATION_LOCALES', env('APP_LOCALE', 'en')),
        ],

        'hash_ids' => [
            'enabled' => env('MODULAR_API_FEATURE_HASH_IDS_ENABLED', false),
            'salt' => env('MODULAR_API_FEATURE_HASH_IDS_KEY', env('APP_KEY')),
            'length' => env('MODULAR_API_FEATURE_HASH_IDS_LENGTH', 20),
            'alphabet' => env('MODULAR_API_FEATURE_HASH_IDS_ALPHABET',
                'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'),
        ],

    ],

];
```

## Getting started

Services directory structure:

```php
App/
    Services/
        DomainA/
            Service1/
                Config/
                Data/
                    Migrations/
                Http/
                    Controllers/
                    Endpoints/
                    Requests/
                    WebEndpoints/
                Mails/
                    Templates/
                Models/
                Resources/
                Providers/
                    MainServiceProvider.php
                Views/
            Service2/
                ...
        DomainB/
            Service1/
                ...
            Service2/
                ...
```

## License

The DBAD License (DBAD). Please see [License File](LICENSE.md) for more information.
