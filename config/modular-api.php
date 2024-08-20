<?php

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

        'hash_ids' => [
            'enabled' => env('MODULAR_API_FEATURE_HASH_IDS_ENABLED', false),
            'salt' => env('MODULAR_API_FEATURE_HASH_IDS_KEY', env('APP_KEY')),
            'length' => env('MODULAR_API_FEATURE_HASH_IDS_LENGTH', 20),
            'alphabet' => env('MODULAR_API_FEATURE_HASH_IDS_ALPHABET',
                'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'),
        ],

    ],

];
