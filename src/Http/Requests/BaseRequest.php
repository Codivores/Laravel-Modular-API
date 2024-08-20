<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi\Http\Requests;

use Codivores\LaravelModularApi\Exceptions\HashIdInvalidException;
use Codivores\LaravelModularApi\Traits\Features\HashId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BaseRequest extends FormRequest
{
    use HashId;

    public array $routeParameters = [];

    public array $encodedInputs = [];

    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * @see FormRequest::all()
     */
    public function all($keys = null): array
    {
        $input = parent::all($keys);

        $this->handleRouteId();

        $input = $this->mergeRouteParametersWithInput($input);

        $input = $this->decodeHashedIds($input);

        return $input;
    }

    public function encodedInput($key = null, $default = null): mixed
    {
        return data_get($this->all($key), $key, $default);
    }

    private function handleRouteId(): bool
    {
        if ($this->route('id') !== null) {
            if (! in_array('id', $this->routeParameters)) {
                $this->routeParameters[] = 'id';
            }
        }

        if ($this->isHashIdsFeatureEnabled()) {
            $resourceClasspath = Str::replaceLast(
                'Http\Requests',
                'Resources',
                get_class($this)
            );
            $resourceClasspath = Str::replaceLast(
                '\\'.Str::afterLast($resourceClasspath, '\\'),
                'Resource',
                $resourceClasspath
            );

            if (class_exists($resourceClasspath)) {
                if ($resourceClasspath::$useHashIds === true) {
                    if (! in_array('id', $this->encodedInputs)) {
                        $this->encodedInputs[] = 'id';
                    }

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Add Route parameters to Request input to allow validation rules.
     */
    private function mergeRouteParametersWithInput(array $input): array
    {
        if (! empty($this->routeParameters)) {
            foreach ($this->routeParameters as $parameter) {
                $input[$parameter] = $this->route($parameter);
            }
        }

        return $input;
    }

    protected function decodeHashedIds(array $input): array
    {
        if ($this->isHashIdsFeatureEnabled() && ! empty($this->encodedInputs)) {
            foreach ($this->encodedInputs as $key) {
                $input = $this->decodeHashedIdInput($input, explode('.', $key), $key);
            }
        }

        return $input;
    }

    private function decodeHashedIdInput($input, $keyList, $currentKey): mixed
    {
        if (empty($keyList)) {
            if (empty($input)) {
                return $input;
            } else {
                $decodedField = $this->decode($input);

                if (empty($decodedField)) {
                    throw new HashIdInvalidException;
                }

                return $decodedField;
            }
        }

        $field = array_shift($keyList);

        if ($field == '*') {
            $input = Arr::wrap($input);

            $fields = $input;
            foreach ($fields as $key => $value) {
                $input[$key] = $this->decodeHashedIdInput($value, $keyList, $currentKey.'['.$key.']');
            }

            return $input;
        }

        if (! array_key_exists($field, $input)) {
            return $input;
        }

        $value = $input[$field];
        $input[$field] = $this->decodeHashedIdInput($value, $keyList, $field);

        return $input;
    }
}