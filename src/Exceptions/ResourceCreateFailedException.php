<?php

namespace Codivores\LaravelModularApi\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ResourceCreateFailedException extends BaseException
{
    protected $code = Response::HTTP_EXPECTATION_FAILED;

    protected $message = 'Failed to create Resource.';
}
