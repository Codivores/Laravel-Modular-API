<?php

declare(strict_types=1);

namespace Codivores\LaravelModularApi\Exceptions;

use Exception;
use Throwable;

abstract class BaseException extends Exception
{
    protected array $errors = [];

    public function __construct(
        ?string $message = null,
        ?int $code = null,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $this->mapMessage($message),
            $this->mapCode($code),
            $previous
        );
    }

    private function mapMessage(?string $message = null): string
    {
        return $message ?? $this->message;
    }

    private function mapCode(?int $code = null): int
    {
        return $code ?? $this->code;
    }

    public function errors(): array
    {
        $errorList = [];

        foreach ($this->errors as $key => $value) {
            $translatedErrorValueList = [];
            // Translation and mutation of each error in array of messages.
            if (is_array($value)) {
                foreach ($value as $translationKey) {
                    $translatedErrorValueList[] = __($translationKey);
                }
            } else {
                $translatedErrorValueList[] = __($value);
            }

            $errorList[$key] = $translatedErrorValueList;
        }

        return $errorList;
    }
}
