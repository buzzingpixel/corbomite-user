<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

use corbomite\user\actions\CreateMigrationsAction;

return [
    'user' => [
        'description' => 'Corbomite Schedule Commands',
        'commands' => [
            'create-migrations' => [
                'description' => 'Adds migrations to create user tables',
                'class' => CreateMigrationsAction::class,
            ],
        ],
    ],
];
