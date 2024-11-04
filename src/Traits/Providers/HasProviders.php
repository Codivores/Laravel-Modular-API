<?php

namespace Codivores\LaravelModularApi\Traits\Providers;

use Codivores\LaravelModularApi\Exceptions\InternalErrorException;
use Illuminate\Support\Facades\File;
use LaravelModularApi;

trait HasProviders
{
    public function loadProviders(): void
    {
        throw_if(! method_exists($this->app, 'register'),
            new InternalErrorException('Class needs to have access to app->register() method.')
        );

        foreach (LaravelModularApi::serviceTree() as $domain => $serviceList) {
            foreach ($serviceList as $service) {
                $this->loadServiceMainServiceProvider($domain, $service);
            }
        }
    }

    private function loadServiceMainServiceProvider(string $domain, string $service): void
    {
        $providerPath = LaravelModularApi::servicePath($domain, $service)
            .DIRECTORY_SEPARATOR.'Providers'
            .DIRECTORY_SEPARATOR.'MainServiceProvider.php';

        if (File::exists($providerPath)) {
            $classPath = LaravelModularApi::serviceClassPath($domain, $service)
                .'Providers\\MainServiceProvider';

            if (class_exists($classPath)) {
                $this->app->register($classPath);
            }
        }
    }
}
