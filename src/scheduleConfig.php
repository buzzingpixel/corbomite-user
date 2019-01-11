<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

use corbomite\user\services\SessionGarbageCollectionService;

return [
    [
        'class' => SessionGarbageCollectionService::class,
        'runEvery' => 'DayAtMidnight',
    ],
];
