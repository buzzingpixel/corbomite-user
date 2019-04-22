<?php

declare(strict_types=1);

use corbomite\user\services\ResetTokenGarbageCollectionService;
use corbomite\user\services\SessionGarbageCollectionService;

return [
    [
        'class' => SessionGarbageCollectionService::class,
        'runEvery' => 'DayAtMidnight',
    ],
    [
        'class' => ResetTokenGarbageCollectionService::class,
        'runEvery' => 'FiveMinutes',
    ],
];
