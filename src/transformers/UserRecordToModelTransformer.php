<?php

declare(strict_types=1);

namespace corbomite\user\transformers;

use corbomite\user\interfaces\UserModelInterface;
use corbomite\user\interfaces\UserRecordToModelTransformerInterface;
use corbomite\user\models\UserModel;
use DateTime;
use DateTimeZone;
use function is_string;
use function json_decode;

class UserRecordToModelTransformer implements UserRecordToModelTransformerInterface
{
    /**
     * @param mixed[] $record
     */
    public function __invoke(array $record) : UserModelInterface
    {
        return $this->transform($record);
    }

    /** @noinspection PhpDocMissingThrowsInspection */

    /**
     * @param mixed[] $record
     */
    public function transform(array $record) : UserModelInterface
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
            is_string($record['user_data']) ? $record['user_data'] : '',
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
