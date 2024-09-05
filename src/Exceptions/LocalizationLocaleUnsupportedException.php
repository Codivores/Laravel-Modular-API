<?php

namespace Codivores\LaravelModularApi\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class LocalizationLocaleUnsupportedException extends BaseException
{
    protected $code = Response::HTTP_PRECONDITION_FAILED;

    protected $message = 'Unsupported language.';
}
