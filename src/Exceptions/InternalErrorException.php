<?php

namespace Codivores\LaravelModularApi\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InternalErrorException extends BaseException
{
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;

    protected $message = 'Internal server error.';
}
