<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi;

use Illuminate\Support\Str;

class LaravelModularApi
{
    public function apiUrl(): string
    {
        return config('modular-api.api.routing.url', '');
    }

    public function apiUrlPrefix(): string
    {
        return config('modular-api.api.routing.url_prefix', '');
    }

    public function apiRoutePrefix(): string
    {
        return config('modular-api.api.routing.route_prefix')
            ? Str::finish(config('modular-api.api.routing.route_prefix'), '.')
            : '';
    }
}
