<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi;

use Codivores\LaravelModularApi\Traits\Services\Util as ServicesUtil;
use Illuminate\Support\Str;

class LaravelModularApi
{
    use ServicesUtil;

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
