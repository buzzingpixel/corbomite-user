<?php

declare(strict_types=1);

namespace corbomite\user\exceptions;

use Exception;
use Throwable;

class UserDoesNotExistException extends Exception
{
    public function __construct(
        string $message = 'User does not exist',
        int $code = 500,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
