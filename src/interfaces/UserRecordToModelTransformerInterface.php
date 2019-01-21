<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\interfaces;

use corbomite\user\data\User\UserRecord;

interface UserRecordToModelTransformerInterface
{
    /**
     * Transforms a user record into a user model
     * @param array $record Array of record values from PDO fetch
     * @return UserModelInterface
     */
    public function __invoke(array $record): UserModelInterface;

    /**
     * Transforms a user record into a user model
     * @param array $record Array of record values from PDO fetch
     * @return UserModelInterface
     */
    public function transform(array $record): UserModelInterface;
}
