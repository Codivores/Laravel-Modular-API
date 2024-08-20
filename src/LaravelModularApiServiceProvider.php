<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi;

use Illuminate\Support\ServiceProvider;

class LaravelModularApiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/modular-api.php',
            'modular-api'
        );
    }

    /**
     * Handle the booting of the service provider.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/modular-api.php' => config_path('modular-api.php'),
        ], 'modular-api-config');
    }
}
