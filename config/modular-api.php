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

    ],

    /*
    |--------------------------------------------------------------------------
    | Modular code configuration
    |--------------------------------------------------------------------------
    */

    'services' => [

        'root_path' => env('MODULAR_API_SERVICES_ROOT_PATH', 'Services'),

    ],

];
