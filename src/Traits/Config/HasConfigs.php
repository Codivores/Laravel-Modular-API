<?php

namespace Codivores\LaravelModularApi\Traits\Config;

use Codivores\LaravelModularApi\Exceptions\InternalErrorException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use LaravelModularApi;

trait HasConfigs
{
    public function loadConfigs(): void
    {
        throw_if(! method_exists($this, 'mergeConfigFrom'),
            new InternalErrorException('Class needs to implement mergeConfigFrom() method.')
        );

        foreach (LaravelModularApi::serviceTree() as $domain => $serviceList) {
            foreach ($serviceList as $service) {
                $this->loadServiceConfigs($domain, $service);
            }
        }
    }

    private function loadServiceConfigs(string $domain, string $service): void
    {
        $configsPath = LaravelModularApi::servicePath($domain, $service)
            .DIRECTORY_SEPARATOR.'Config';

        if (File::isDirectory($configsPath)) {
            $files =
                Arr::where(
                    File::allFiles($configsPath),
                    function ($file) {
                        return $file->getExtension() === 'php';
                    });

            foreach ($files as $file) {
                $this->mergeConfigFrom($file, File::name($file));
            }
        }
    }
}
