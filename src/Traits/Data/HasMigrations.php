<?php

namespace Codivores\LaravelModularApi\Traits\Data;

use Codivores\LaravelModularApi\Exceptions\InternalErrorException;
use Illuminate\Support\Facades\File;
use LaravelModularApi;

trait HasMigrations
{
    public function loadMigrations(): void
    {
        throw_if(! method_exists($this, 'loadMigrationsFrom'),
            new InternalErrorException('Class needs to implement loadMigrationsFrom() method.')
        );

        foreach (LaravelModularApi::servicePathList() as $servicePath) {
            $this->loadServiceMigrations($servicePath);
        }
    }

    private function loadServiceMigrations(string $servicePath): void
    {
        $migrationsPath = $servicePath.DIRECTORY_SEPARATOR.'Data'.DIRECTORY_SEPARATOR.'Migrations';

        if (File::isDirectory($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }
}
