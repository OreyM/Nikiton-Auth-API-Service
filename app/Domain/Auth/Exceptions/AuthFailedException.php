<?php

namespace App\Domain\Auth\Exceptions;

class AuthFailedException extends \Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    )
    {
        $message = $message ?: trans('auth.failed');

        parent::__construct($message, $code, $previous);
    }
}
