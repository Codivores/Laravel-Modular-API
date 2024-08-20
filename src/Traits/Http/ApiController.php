<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi\Traits\Http;

use Closure;
use Illuminate\Support\Facades\Pipeline;

trait ApiController
{
    public mixed $payload = null;

    public array $actions = [];

    public function process(?Closure $callback = null): mixed
    {
        $payload = Pipeline::send($this->payload)
            ->through($this->actions)
            ->thenReturn();

        return $callback instanceof Closure
            ? $callback($payload)
            : $payload;
    }
}
