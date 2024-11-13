<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi\Traits\Http;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use LaravelModularApi;
use Symfony\Component\Finder\SplFileInfo;

trait WebRoute
{
    public function loadWebRoutes(): void
    {
        foreach (LaravelModularApi::servicePathList() as $servicePath) {
            $this->loadServiceWebRoutes($servicePath);
        }
    }

    public function webRouteGroup(?SplFileInfo $file = null, ?string $prefix = null): array
    {
        return [
            'middleware' => $this->webMiddlewares(),
            'domain' => LaravelModularApi::webUrl(),
        ];
    }

    private function loadServiceWebRoutes(string $servicePath): void
    {
        $routesPath = $servicePath.DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'WebEndpoints';

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
                $this->loadServiceWebRoutesFromFile($file);
            }
        }
    }

    private function loadServiceWebRoutesFromFile(SplFileInfo $file): void
    {
        $routeGroupArray = $this->webRouteGroup(file: $file);

        Route::group($routeGroupArray, function ($router) use ($file) {
            require $file->getPathname();
        });
    }

    private function webMiddlewares(): array
    {
        return array_filter([
            'web',
        ]);
    }
}
