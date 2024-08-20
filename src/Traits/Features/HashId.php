<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi\Traits\Features;

use Codivores\LaravelModularApi\Exceptions\FeatureInvalidException;
use Codivores\LaravelModularApi\Exceptions\HashIdInvalidException;
use Hashids\Hashids;
use Illuminate\Support\Str;

trait HashId
{
    public static bool $useHashIds = true;

    public function encode($value, array $options = []): string|int|null
    {
        if (! $this->isHashIdsFeatureEnabled()) {
            return $value;
        }

        $encoder = $this->encoder($options);

        return $encoder->encode($value);
    }

    public function decode($value, array $options = [])
    {
        if (! $this->isHashIdsFeatureEnabled()) {
            return $value;
        }

        if (is_null($value) || Str::lower($value) == 'null') {
            return null;
        }

        $encoder = $this->encoder($options);
        $decodedValue = $encoder->decode($value);
        throw_if(empty($decodedValue), new HashIdInvalidException);

        return $decodedValue[0];
    }

    private function encoder(array $options = []): ?Hashids
    {
        return new Hashids(
            $options['salt'] ?? config('modular-api.features.hash_ids.salt'),
            $options['length'] ?? config('modular-api.features.hash_ids.length'),
            $options['alphabet'] ?? config('modular-api.features.hash_ids.alphabet'),
        );
    }

    private function isHashIdsFeatureEnabled(): bool
    {
        $enabled = config('modular-api.features.hash_ids.enabled', false) && self::$useHashIds;

        throw_if($enabled && ! class_exists(Hashids::class),
            new FeatureInvalidException('Hashids package is not installed or loaded.'));

        return $enabled;
    }
}
