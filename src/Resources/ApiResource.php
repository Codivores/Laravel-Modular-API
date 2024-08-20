<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi\Resources;

use Codivores\LaravelModularApi\Traits\Features\HashId;
use Illuminate\Http\Request;
use TiMacDonald\JsonApi\JsonApiResource;

class ApiResource extends JsonApiResource
{
    use HashId;

    public function toId(Request $request)
    {
        $id = parent::toId($request);

        if (! $this->isHashIdsFeatureEnabled()) {
            return $id;
        }

        return $this->encode($id);
    }
}
