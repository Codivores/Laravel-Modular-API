<?php

namespace Codivores\LaravelModularApi\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class EndpointNotFoundException extends BaseException
{
    protected $code = Response::HTTP_NOT_FOUND;

    protected $message = 'The requested Endpoint was not found.';
}
