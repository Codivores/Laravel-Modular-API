<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as LaravelExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends LaravelExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (BaseException $e) {
            return $this->toResponse($e);
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return $this->toResponse(new EndpointNotFoundException);
        });
    }

    private function toResponse(BaseException $e): JsonResponse
    {
        if (config('app.debug')) {
            $response = [
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ];
        } else {
            $response = [
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ];
        }

        return response()
            ->json($response, (int) $e->getCode());
    }
}
