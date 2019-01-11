<?php
declare(strict_types=1);

namespace corbomite\user\exceptions;

use Exception;
use Throwable;

class InvalidUserModelException extends Exception
{
    public function __construct(
        string $message = 'The user model is not valid',
        int $code = 500,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
