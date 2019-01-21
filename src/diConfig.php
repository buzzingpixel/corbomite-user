<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

use corbomite\di\Di;
use corbomite\db\PDO;
use corbomite\user\UserApi;
use Zend\Diactoros\Response;
use Ramsey\Uuid\UuidFactory;
use buzzingpixel\cookieapi\CookieApi;
use corbomite\flashdata\FlashDataApi;
use corbomite\db\Factory as DbFactory;
use corbomite\db\Factory as OrmFactory;
use corbomite\requestdatastore\DataStore;
use corbomite\user\http\actions\LogInAction;
use corbomite\user\actions\CreateUserAction;
use corbomite\user\services\SaveUserService;
use corbomite\db\services\BuildQueryService;
use corbomite\user\services\LogUserInService;
use corbomite\user\services\FetchUserService;
use corbomite\user\services\FetchUsersService;
use corbomite\cli\services\CliQuestionService;
use corbomite\user\services\RegisterUserService;
use corbomite\user\services\SetNewPasswordService;
use corbomite\user\actions\CreateMigrationsAction;
use Symfony\Component\Console\Output\ConsoleOutput;
use corbomite\user\twigextensions\UserTwigExtension;
use corbomite\user\services\FetchCurrentUserService;
use corbomite\user\services\LogCurrentUserOutService;
use corbomite\user\services\CreateUserSessionService;
use corbomite\user\services\GeneratePasswordResetToken;
use corbomite\user\services\ValidateUserPasswordService;
use corbomite\user\services\ResetPasswordByTokenService;
use corbomite\user\transformers\UserRecordToModelTransformer;
use corbomite\user\services\SessionGarbageCollectionService;
use corbomite\user\services\ResetTokenGarbageCollectionService;
use corbomite\user\services\GetUserByPasswordResetTokenService;

return [
    CreateMigrationsAction::class => function () {
        return new CreateMigrationsAction(
            __DIR__ . '/migrations',
            new ConsoleOutput()
        );
    },
    CreateUserAction::class => function () {
        return new CreateUserAction(
            Di::get(UserApi::class),
            new ConsoleOutput(),
            Di::get(CliQuestionService::class)
        );
    },
    UserApi::class => function () {
        return new UserApi(new Di(), new DbFactory());
    },
    RegisterUserService::class => function () {
        return new RegisterUserService(Di::get(SaveUserService::class));
    },
    SaveUserService::class => function () {
        return new SaveUserService(Di::get(PDO::class), new UuidFactory());
    },
    FetchUserService::class => function () {
        return new FetchUserService(
            new DbFactory(),
            Di::get(FetchUsersService::class)
        );
    },
    FetchUsersService::class => function () {
        return new FetchUsersService(
            Di::get(PDO::class),
            Di::get(BuildQueryService::class),
            Di::get(UserRecordToModelTransformer::class)
        );
    },
    FetchCurrentUserService::class => function () {
        return new FetchCurrentUserService(
            new OrmFactory(),
            Di::get(CookieApi::class),
            Di::get(FetchUserService::class)
        );
    },
    ValidateUserPasswordService::class => function () {
        return new ValidateUserPasswordService(
            Di::get(FetchUserService::class)
        );
    },
    CreateUserSessionService::class => function () {
        return new CreateUserSessionService(
            new UuidFactory(),
            new OrmFactory(),
            Di::get(FetchUserService::class)
        );
    },
    LogUserInService::class => function () {
        return new LogUserInService(
            Di::get(ValidateUserPasswordService::class),
            Di::get(FetchUserService::class),
            Di::get(SaveUserService::class),
            Di::get(CreateUserSessionService::class),
            Di::get(CookieApi::class)
        );
    },
    LogCurrentUserOutService::class => function () {
        return new LogCurrentUserOutService(
            new OrmFactory(),
            Di::get(CookieApi::class)
        );
    },
    SessionGarbageCollectionService::class => function () {
        return new SessionGarbageCollectionService(Di::get(PDO::class));
    },
    GeneratePasswordResetToken::class => function () {
        return new GeneratePasswordResetToken(
            new UuidFactory(),
            new OrmFactory()
        );
    },
    ResetTokenGarbageCollectionService::class => function () {
        return new ResetTokenGarbageCollectionService(Di::get(PDO::class));
    },
    GetUserByPasswordResetTokenService::class => function () {
        return new GetUserByPasswordResetTokenService(
            new OrmFactory(),
            Di::get(FetchUserService::class)
        );
    },
    ResetPasswordByTokenService::class => function () {
        return new ResetPasswordByTokenService(
            Di::get(GetUserByPasswordResetTokenService::class),
            Di::get(SaveUserService::class),
            Di::get(PDO::class)
        );
    },
    SetNewPasswordService::class => function () {
        return new SetNewPasswordService(Di::get(SaveUserService::class));
    },
    UserRecordToModelTransformer::class => function () {
        return new UserRecordToModelTransformer();
    },
    LogInAction::class => function () {
        return new LogInAction(
            Di::get(UserApi::class),
            new Response(),
            Di::get(FlashDataApi::class),
            Di::get(DataStore::class)
        );
    },
    UserTwigExtension::class => function () {
        return new UserTwigExtension(Di::get(UserApi::class));
    },
];
