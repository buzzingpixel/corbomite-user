<?php

declare(strict_types=1);

use corbomite\user\actions\CreateMigrationsAction;
use corbomite\user\actions\CreateUserAction;

return [
    'user' => [
        'description' => 'Corbomite Schedule Commands',
        'commands' => [
            'create-migrations' => [
                'description' => 'Adds migrations to create user tables',
                'class' => CreateMigrationsAction::class,
            ],
            'create' => [
                'description' => 'Creates a user',
                'class' => CreateUserAction::class,
            ],
        ],
    ],
];
