<?php

namespace Codivores\LaravelModularApi\Traits\Http;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function json(
        string|array|null $message = null,
        int $status = 200,
        array $headers = [],
        $options = 0
    ): JsonResponse {
        return new JsonResponse($message, $status, $headers, $options);
    }

    public function accepted(
        string|array|null $message = null,
        int $status = 202,
        array $headers = [],
        $options = 0
    ): JsonResponse {
        return $this->json($message, $status, $headers, $options);
    }

    public function created(
        string|array|null $message = null,
        int $status = 201,
        array $headers = [],
        $options = 0
    ): JsonResponse {
        return $this->json($message, $status, $headers, $options);
    }

    public function deleted(
        string|array|null $message = null,
        int $status = 204,
        array $headers = [],
        $options = 0
    ): JsonResponse {
        return $this->json($message, $status, $headers, $options);
    }

    public function noContent(
        int $status = 204,
        array $headers = [],
        $options = 0
    ): JsonResponse {
        return $this->json(null, $status, $headers, $options);
    }
}
