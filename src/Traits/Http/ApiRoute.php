<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi\Traits\Http;

use Codivores\LaravelModularApi\Http\Middlewares\Localization;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use LaravelModularApi;
use Symfony\Component\Finder\SplFileInfo;

trait ApiRoute
{
    public function loadRoutes(): void
    {
        foreach (LaravelModularApi::servicePathList() as $servicePath) {
            $this->loadServiceRoutes($servicePath);
        }
    }

    public function routeGroup(?SplFileInfo $file = null, ?string $prefix = null): array
    {
        $prefixList = $this->apiPrefixesFromFile($file);

        return [
            'middleware' => $this->middlewares(),
            'domain' => LaravelModularApi::apiUrl(),
            'prefix' => LaravelModularApi::apiUrlPrefix()
                .($prefix !== null
                    ? $prefix
                    : implode('/', $prefixList)
                ),
            'meta' => $prefixList,
        ];
    }

    private function loadServiceRoutes(string $servicePath): void
    {
        $routesPath = $servicePath.DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Endpoints';

        if (File::isDirectory($routesPath)) {
            $files =
                Arr::sort(
                    Arr::where(
                        File::allFiles($routesPath),
                        function ($file) {
                            return $file->getExtension() === 'php';
                        }),
                    function ($file) {
                        return $file->getFilename();
                    });

            foreach ($files as $file) {
                $this->loadServiceRoutesFromFile($file);
            }
        }
    }

    private function loadServiceRoutesFromFile(SplFileInfo $file): void
    {
        $routeGroupArray = $this->routeGroup(file: $file);

        Route::group($routeGroupArray, function ($router) use ($file) {
            require $file->getPathname();
        });
    }

    private function middlewares(): array
    {
        return array_filter([
            'api',
            $this->middlewareRateLimiter(),
            $this->middlewareLocalization(),
        ]);
    }

    private function middlewareRateLimiter(): ?string
    {
        if (config('modular-api.features.rate_limiting.enabled')) {
            RateLimiter::for('api', function (Request $request) {
                return Limit::perMinutes(config('modular-api.features.rate_limiting.expires'),
                    config('modular-api.features.rate_limiting.attempts'))->by($request->user()?->id ?: $request->ip());
            });

            return 'throttle:api';
        }

        return null;
    }

    private function middlewareLocalization(): ?string
    {
        if (config('modular-api.features.localization.enabled')) {
            return Localization::class;
        }

        return null;
    }

    private function apiPrefixesFromFile(SplFileInfo $file): array
    {
        $prefixList = [];

        if (! config('modular-api.api.routing.enable_version_prefix') && ! config('modular-api.api.routing.enable_type_prefix')) {
            return $prefixList;
        }

        $filenameExploded = explode('.', $file->getFilenameWithoutExtension());

        if (config('modular-api.api.routing.enable_type_prefix') && count($filenameExploded) > 0) {
            $prefixList['type'] = array_pop($filenameExploded);
        }

        if (config('modular-api.api.routing.enable_version_prefix') && count($filenameExploded) > 0) {
            $prefixList['version'] = array_pop($filenameExploded);
        }

        return $prefixList;
    }

    public function registerMacros(): void
    {
        Route::macro('endpoints', function ($domain, $service, $resource = null, $options = []) {
            $actionDefaultList = [
                'get' => ['method' => 'get'],
                'find' => ['method' => 'get', 'uri' => '{'.($options['identifier'] ?? 'id').'}'],
                'create' => ['method' => 'post'],
                'update' => ['method' => 'patch', 'uri' => '{'.($options['identifier'] ?? 'id').'}'],
                'destroy' => ['method' => 'delete', 'uri' => '{'.($options['identifier'] ?? 'id').'}'],
            ];

            if (isset($options['actions'])) {
                $actionList = Arr::only($actionDefaultList, $options['actions']);
            } else {
                $actionList = $actionDefaultList;
            }

            $routePrefix = LaravelModularApi::apiRoutePrefix();
            if ($this->hasGroupStack()) {
                $type = data_get($this->getGroupStack(), '0.meta.type');
                if ($type !== null) {
                    $routePrefix .= $type.'.';
                }
            }

            $domainName = (isset($options['uriDomain']) && ! empty($options['uriDomain']))
                ? $options['uriDomain']
                : ((($options['withoutDomain'] ?? false) === true)
                    ? ''
                    : LaravelModularApi::domainSlug($domain)
                );
            $endpointName = (isset($options['uriEndpoint']) && ! empty($options['uriEndpoint']))
                ? $options['uriEndpoint']
                : ((($options['withoutService'] ?? false) === true)
                    ? ''
                    : LaravelModularApi::resourceSlug($resource ?? $service)
                );
            $resourceName = Str::studly($resource ?? $service);

            $controllerClassPath = LaravelModularApi::servicesClassPathRoot()
                .Str::studly($domain)
                .'\\'.Str::studly($service)
                .'\\Http\Controllers\\';

            Route::prefix($domainName)
                ->name($routePrefix.(empty($domainName) ? '' : $domainName.'.'))
                ->group(function () use ($options, $actionList, $endpointName, $resourceName, $controllerClassPath) {
                    Route::prefix($endpointName)
                        ->name((empty($endpointName) ? '' : $endpointName.'.'))
                        ->group(function () use ($options, $actionList, $resourceName, $controllerClassPath) {
                            foreach ($actionList as $actionName => $actionArgs) {
                                Route::{$actionArgs['method']}(($actionArgs['uri'] ?? ''),
                                    $controllerClassPath.$resourceName.Str::studly($actionName).'Controller')->name($actionName);
                            }

                            if (is_array($options['extraActions'] ?? null) && count($options['extraActions']) > 0) {
                                foreach ($options['extraActions'] as $actionName => $actionArgs) {
                                    Route::{$actionArgs['method']}(($actionArgs['uri'] ?? ''),
                                        $controllerClassPath.$resourceName.Str::studly($actionName).'Controller')->name($actionName);
                                }
                            }
                        });
                });
        });
    }
}
