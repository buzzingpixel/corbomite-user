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
use corbomite\user\interfaces\UserModelInterface;
use corbomite\user\interfaces\UserRecordToModelTransformerInterface;

class UserRecordToModelTransformer implements UserRecordToModelTransformerInterface
{
    public function __invoke(array $record): UserModelInterface
    {
        return $this->transform($record);
    }

    public function transform(array $record): UserModelInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $addedAt = new DateTime(
            $record['added_at'],
            new DateTimeZone($record['added_at_time_zone'])
        );

        $model = new UserModel();
        $model->setGuidAsBytes($record['guid']);
        $model->emailAddress($record['email_address']);
        $model->passwordHash($record['password_hash']);
        $model->userData(json_decode(
            \is_string($record['user_data']) ? $record['user_data'] : '',
            true
        ));
        $model->addedAt($addedAt);

        unset(
            $record['guid'],
            $record['email_address'],
            $record['password_hash'],
            $record['user_data'],
            $record['added_at'],
            $record['added_at_time_zone']
        );

        $model->extendedProperties($record);

        return $model;
    }
}
