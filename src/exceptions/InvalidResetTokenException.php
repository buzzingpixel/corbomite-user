<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\exceptions;

use Exception;
use Throwable;

class InvalidResetTokenException extends Exception
{
    public function __construct(
        string $message = 'The specified token is invalid',
        int $code = 500,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
