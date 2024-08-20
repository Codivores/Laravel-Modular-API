<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi;

use Codivores\LaravelModularApi\Exceptions\Handler as ExceptionHandler;
use Codivores\LaravelModularApi\Providers\RouteServiceProvider;
use Codivores\LaravelModularApi\Traits\Data\HasMigrations;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Support\ServiceProvider;

class LaravelModularApiServiceProvider extends ServiceProvider
{
    use HasMigrations;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/modular-api.php',
            'modular-api'
        );

        $this->registerExceptionHandler();

        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Handle the booting of the service provider.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/modular-api.php' => config_path('modular-api.php'),
        ], 'modular-api-config');

        $this->loadMigrations();
    }
    private function registerExceptionHandler(): void
    {
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            ExceptionHandler::class
        );

        $using ??= fn () => true;

        $this->app->afterResolving(
            ExceptionHandler::class,
            fn ($handler) => $using(new Exceptions($handler)),
        );
    }
}
