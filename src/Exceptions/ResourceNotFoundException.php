<?php

namespace Codivores\LaravelModularApi\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ResourceNotFoundException extends BaseException
{
    protected $code = Response::HTTP_NOT_FOUND;

    protected $message = 'The requested Resource was not found.';
}
