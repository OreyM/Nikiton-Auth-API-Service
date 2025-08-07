<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Domain\User\Exceptions;

class UserNotFoundException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        if (empty($message)) {
            $message = "User was not found.";
        }

        parent::__construct($message, $code, $previous);
    }
}
