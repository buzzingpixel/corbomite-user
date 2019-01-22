<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace corbomite\user\data\UserPasswordResetToken;

use Atlas\Table\Row;

/**
 * @property mixed $guid varchar(255) NOT NULL
 * @property mixed $user_guid text(65535) NOT NULL
 * @property mixed $added_at datetime NOT NULL
 * @property mixed $added_at_time_zone varchar(255) NOT NULL
 */
class UserPasswordResetTokenRow extends Row
{
    protected $cols = [
        'guid' => null,
        'user_guid' => null,
        'added_at' => null,
        'added_at_time_zone' => null,
    ];
}
