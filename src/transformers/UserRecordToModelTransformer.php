<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\transformers;

use DateTime;
use DateTimeZone;
use corbomite\user\models\UserModel;
use corbomite\user\data\User\UserRecord;
use corbomite\user\interfaces\UserModelInterface;
use corbomite\user\interfaces\UserRecordToModelTransformerInterface;

class UserRecordToModelTransformer implements UserRecordToModelTransformerInterface
{
    public function __invoke(UserRecord $record): UserModelInterface
    {
        return $this->transform($record);
    }

    public function transform(UserRecord $record): UserModelInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $addedAt = new DateTime(
            $record->added_at,
            new DateTimeZone($record->added_at_time_zone)
        );

        $addedAt->setTimezone(new DateTimeZone(date_default_timezone_get()));

        return new UserModel([
            'guid' => $record->guid,
            'emailAddress' => $record->email_address,
            'passwordHash' => $record->password_hash,
            'userData' => json_decode(
                \is_string($record->user_data) ? $record->user_data : '',
                true
            ),
            'addedAt' => $addedAt,
        ]);
    }
}
