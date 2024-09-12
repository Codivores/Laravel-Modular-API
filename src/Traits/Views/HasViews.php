<?php

namespace Codivores\LaravelModularApi\Traits\Views;

use Codivores\LaravelModularApi\Exceptions\InternalErrorException;
use Illuminate\Support\Facades\File;
use LaravelModularApi;

trait HasViews
{
    public function loadViews(): void
    {
        throw_if(! method_exists($this, 'loadViewsFrom'),
            new InternalErrorException('Class needs to implement loadViewsFrom() method.')
        );

        foreach (LaravelModularApi::serviceTree() as $domain => $serviceList) {
            foreach ($serviceList as $service) {
                $this->loadServiceMails($domain, $service);
                $this->loadServiceViews($domain, $service);
            }
        }
    }

    private function loadServiceMails(string $domain, string $service): void
    {
        $mailsPath = LaravelModularApi::servicePath($domain, $service)
            .DIRECTORY_SEPARATOR.'Mails'
            .DIRECTORY_SEPARATOR.'Templates';

        if (File::isDirectory($mailsPath)) {
            $this->loadViewsFrom($mailsPath, $this->viewNamespace($domain, $service));
        }
    }

    private function loadServiceViews(string $domain, string $service): void
    {
        $mailsPath = LaravelModularApi::servicePath($domain, $service)
            .DIRECTORY_SEPARATOR.'Views';

        if (File::isDirectory($mailsPath)) {
            $this->loadViewsFrom($mailsPath, $this->viewNamespace($domain, $service));
        }
    }

    private function viewNamespace(string $domain, string $service): string
    {
        return $domain.'.'.$service;
    }
}
