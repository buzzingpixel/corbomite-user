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
     * @param UserRecord $record
     * @return UserModelInterface
     */
    public function __invoke(UserRecord $record): UserModelInterface;

    /**
     * Transforms a user record into a user model
     * @param UserRecord $record
     * @return UserModelInterface
     */
    public function transform(UserRecord $record): UserModelInterface;
}
