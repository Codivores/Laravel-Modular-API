<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi\Http\Controllers;

use Codivores\LaravelModularApi\Traits\Http\ApiController;
use Codivores\LaravelModularApi\Traits\Http\ApiResponse;

class BaseController
{
    use ApiController;
    use ApiResponse;
}
