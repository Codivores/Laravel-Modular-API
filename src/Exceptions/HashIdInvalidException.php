<?php

namespace Codivores\LaravelModularApi\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HashIdInvalidException extends BaseException
{
    protected $code = Response::HTTP_BAD_REQUEST;

    protected $message = 'ID input is incorrect.';
}
