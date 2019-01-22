<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace corbomite\user\data\UserSession;

use Atlas\Table\Row;

/**
 * @property mixed $guid varchar(255) NOT NULL
 * @property mixed $user_guid text(65535) NOT NULL
 * @property mixed $added_at datetime NOT NULL
 * @property mixed $added_at_time_zone varchar(255) NOT NULL
 * @property mixed $last_touched_at datetime NOT NULL
 * @property mixed $last_touched_at_time_zone varchar(255) NOT NULL
 */
class UserSessionRow extends Row
{
    protected $cols = [
        'guid' => null,
        'user_guid' => null,
        'added_at' => null,
        'added_at_time_zone' => null,
        'last_touched_at' => null,
        'last_touched_at_time_zone' => null,
    ];
}
