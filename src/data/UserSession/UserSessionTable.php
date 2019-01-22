<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace corbomite\user\data\UserSession;

use Atlas\Table\Table;

/**
 * @method UserSessionRow|null fetchRow($primaryVal)
 * @method UserSessionRow[] fetchRows(array $primaryVals)
 * @method UserSessionTableSelect select(array $whereEquals = [])
 * @method UserSessionRow newRow(array $cols = [])
 * @method UserSessionRow newSelectedRow(array $cols)
 */
class UserSessionTable extends Table
{
    const DRIVER = 'mysql';

    const NAME = 'user_sessions';

    const COLUMNS = [
        'guid' => [
            'name' => 'guid',
            'type' => 'varchar',
            'size' => 255,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => true,
            'options' => null,
        ],
        'user_guid' => [
            'name' => 'user_guid',
            'type' => 'text',
            'size' => 65535,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'added_at' => [
            'name' => 'added_at',
            'type' => 'datetime',
            'size' => null,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'added_at_time_zone' => [
            'name' => 'added_at_time_zone',
            'type' => 'varchar',
            'size' => 255,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'last_touched_at' => [
            'name' => 'last_touched_at',
            'type' => 'datetime',
            'size' => null,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'last_touched_at_time_zone' => [
            'name' => 'last_touched_at_time_zone',
            'type' => 'varchar',
            'size' => 255,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
    ];

    const COLUMN_NAMES = [
        'guid',
        'user_guid',
        'added_at',
        'added_at_time_zone',
        'last_touched_at',
        'last_touched_at_time_zone',
    ];

    const COLUMN_DEFAULTS = [
        'guid' => null,
        'user_guid' => null,
        'added_at' => null,
        'added_at_time_zone' => null,
        'last_touched_at' => null,
        'last_touched_at_time_zone' => null,
    ];

    const PRIMARY_KEY = [
        'guid',
    ];

    const AUTOINC_COLUMN = null;

    const AUTOINC_SEQUENCE = null;
}
