<?php

namespace Codivores\LaravelModularApi\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class FeatureInvalidException extends BaseException
{
    protected $code = Response::HTTP_BAD_REQUEST;

    protected $message = 'Feature disabled or not configured.';
}
