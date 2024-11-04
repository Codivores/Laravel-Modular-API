<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi\Traits\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait Util
{
    public function domainNameList(): array
    {
        return collect($this->domainPathList())
            ->map(function ($path) {
                return basename($path);
            })
            ->toArray();
    }

    public function domainPathList(): array
    {
        $servicesPath = app_path(
            config('modular-api.services.root_path')
        );

        return File::isDirectory($servicesPath)
            ? File::directories($servicesPath)
            : [];
    }

    public function servicePath(string $domain, string $service): string
    {
        return app_path(
            config('modular-api.services.root_path')
            .DIRECTORY_SEPARATOR
            .$domain
            .DIRECTORY_SEPARATOR
            .$service
        );
    }

    public function servicePathList(): array
    {
        $servicePathList = [];
        foreach ($this->domainNameList() as $name) {
            foreach ($this->servicePathListForDomain($name) as $servicePath) {
                $servicePathList[] = $servicePath;
            }
        }

        return $servicePathList;
    }

    public function servicePathListForDomain(string $domain): array
    {
        return File::directories(
            app_path(
                config('modular-api.services.root_path')
                .DIRECTORY_SEPARATOR
                .$domain
            )
        );
    }

    public function serviceTree(): array
    {
        $serviceTree = [];

        foreach ($this->domainNameList() as $name) {
            $domainTree = collect($this->servicePathListForDomain($name))
                ->map(function ($servicePath) {
                    return basename($servicePath);
                })
                ->toArray();

            if (count($domainTree) > 0) {
                $serviceTree[$name] = $domainTree;
            }
        }

        return $serviceTree;
    }

    public function servicesClassPathRoot(): string
    {
        return 'App\\'
            .config('modular-api.services.root_path')
            .'\\';
    }

    public function serviceClassPath(string $domain, string $service): string
    {
        return $this->servicesClassPathRoot()
            .$domain
            .'\\'
            .$service
            .'\\';
    }

    public function domainFromClass(object|string $class): string
    {
        return Str::before(
            Str::replaceStart(
                $this->servicesClassPathRoot(),
                '',
                get_class($class)
            ),
            '\\'
        );
    }

    public function resourceFromClass(object|string $class): string
    {
        $delimiter = '_';

        $classBaseNameExploded = explode(
            $delimiter,
            Str::snake(
                class_basename($class),
                $delimiter
            )
        );

        return Str::studly(
            implode(
                $delimiter,
                Arr::take(
                    $classBaseNameExploded,
                    count($classBaseNameExploded) - 1
                )
            )
        );
    }

    public function domainSlug(string $resource): string
    {
        return Str::kebab($resource);
    }

    public function resourceSlug(string $resource): string
    {
        return Str::plural(
            Str::kebab($resource)
        );
    }
}
