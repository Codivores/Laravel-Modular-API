<?php

namespace Codivores\LaravelModularApi\Traits\Resources;

use LaravelModularApi;
use TiMacDonald\JsonApi\Link;

trait HasLinks
{
    public bool $hasSelfLink = false;

    public function toLinks($request): array
    {
        return $this->hasSelfLink
            ? [
                Link::self($this->selfRoute($request)),
            ]
            : [];
    }

    public function selfRoute($request, $resourceName = null, $route = null, $action = null, $prefix = null): string
    {
        if ($route === null) {
            $resourceName = $resourceName ?? LaravelModularApi::resourceFromClass($this);

            $route = LaravelModularApi::domainSlug(
                LaravelModularApi::domainFromClass($this)
            )
                .'.'
                .LaravelModularApi::resourceSlug($resourceName);
        }

        return route(
            ($prefix ?? LaravelModularApi::apiRoutePrefix())
            .$route
            .($action ? '.'.$action : '.find'),
            $this->selfRouteId($request)
        );
    }

    public function selfRouteId($request)
    {
        return $this->toId($request);
    }
}
