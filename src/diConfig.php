<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

use corbomite\di\Di;
use corbomite\user\UserApi;
use Ramsey\Uuid\UuidFactory;
use corbomite\db\Factory as OrmFactory;
use corbomite\user\services\SaveUserService;
use corbomite\user\services\RegisterUserService;
use corbomite\user\actions\CreateMigrationsAction;
use Symfony\Component\Console\Output\ConsoleOutput;

return [
    CreateMigrationsAction::class => function () {
        return new CreateMigrationsAction(
            __DIR__ . '/migrations',
            new ConsoleOutput()
        );
    },
    UserApi::class => function () {
        return new UserApi(new Di());
    },
    RegisterUserService::class => function () {
        return new RegisterUserService(Di::get(SaveUserService::class));
    },
    SaveUserService::class => function () {
        return new SaveUserService(new OrmFactory(), new UuidFactory());
    },
];
