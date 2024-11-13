<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi\Providers;

use Codivores\LaravelModularApi\Traits\Http\ApiRoute;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    use ApiRoute;

    public function boot(): void
    {
        $this->registerApiMacros();
        $this->loadApiRoutes();
    }
}
