<?php

namespace Codivores\LaravelModularApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Codivores\LaravelModularApi\LaravelModularApi
 */
class LaravelModularApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Codivores\LaravelModularApi\LaravelModularApi::class;
    }
}
