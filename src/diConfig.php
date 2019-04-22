<?php

declare(strict_types=1);

use buzzingpixel\cookieapi\CookieApi;
use Composer\Autoload\ClassLoader;
use corbomite\cli\services\CliQuestionService;
use corbomite\db\Factory as DbFactory;
use corbomite\db\Factory as OrmFactory;
use corbomite\db\PDO;
use corbomite\db\services\BuildQueryService;
use corbomite\di\Di;
use corbomite\events\EventDispatcher;
use corbomite\flashdata\FlashDataApi;
use corbomite\requestdatastore\DataStore;
use corbomite\user\actions\CreateMigrationsAction;
use corbomite\user\actions\CreateUserAction;
use corbomite\user\http\actions\LogInAction;
use corbomite\user\PhpCalls;
use corbomite\user\services\CreateUserSessionService;
use corbomite\user\services\DeleteUserService;
use corbomite\user\services\FetchCurrentUserService;
use corbomite\user\services\FetchUserService;
use corbomite\user\services\FetchUsersService;
use corbomite\user\services\GeneratePasswordResetToken;
use corbomite\user\services\GetUserByPasswordResetTokenService;
use corbomite\user\services\LogCurrentUserOutService;
use corbomite\user\services\LogUserInService;
use corbomite\user\services\RegisterUserService;
use corbomite\user\services\ResetPasswordByTokenService;
use corbomite\user\services\ResetTokenGarbageCollectionService;
use corbomite\user\services\SaveUserService;
use corbomite\user\services\SessionGarbageCollectionService;
use corbomite\user\services\SetNewPasswordService;
use corbomite\user\services\ValidateUserPasswordService;
use corbomite\user\transformers\UserRecordToModelTransformer;
use corbomite\user\twigextensions\UserTwigExtension;
use corbomite\user\UserApi;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Filesystem;
use Zend\Diactoros\Response;

return [
    CreateMigrationsAction::class => static function () {
        $appBasePath = null;

        if (defined('APP_BASE_PATH')) {
            $appBasePath = APP_BASE_PATH;
        }

        if (! $appBasePath) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $reflection = new ReflectionClass(ClassLoader::class);

            $appBasePath = dirname($reflection->getFileName(), 3);
        }

        return new CreateMigrationsAction(
            __DIR__ . '/migrations',
            new ConsoleOutput(),
            $appBasePath,
            new Filesystem(),
            new PhpCalls()
        );
    },
    CreateUserAction::class => static function () {
        return new CreateUserAction(
            Di::get(UserApi::class),
            new ConsoleOutput(),
            Di::get(CliQuestionService::class)
        );
    },
    CreateUserSessionService::class => static function () {
        return new CreateUserSessionService(
            new OrmFactory(),
            Di::get(FetchUserService::class),
            Di::get('UuidFactoryWithOrderedTimeCodec')
        );
    },
    DeleteUserService::class => static function () {
        return new DeleteUserService(
            Di::get(PDO::class),
            Di::get(EventDispatcher::class)
        );
    },
    FetchCurrentUserService::class => static function () {
        return new FetchCurrentUserService(
            new OrmFactory(),
            Di::get(CookieApi::class),
            Di::get(FetchUserService::class),
            Di::get('UuidFactoryWithOrderedTimeCodec')
        );
    },
    FetchUserService::class => static function () {
        return new FetchUserService(
            new DbFactory(),
            Di::get(FetchUsersService::class),
            Di::get('UuidFactoryWithOrderedTimeCodec')
        );
    },
    FetchUsersService::class => static function () {
        return new FetchUsersService(
            Di::get(PDO::class),
            Di::get(BuildQueryService::class),
            Di::get(UserRecordToModelTransformer::class)
        );
    },
    GeneratePasswordResetToken::class => static function () {
        return new GeneratePasswordResetToken(
            new OrmFactory(),
            Di::get('UuidFactoryWithOrderedTimeCodec')
        );
    },
    LogCurrentUserOutService::class => static function () {
        return new LogCurrentUserOutService(
            new OrmFactory(),
            Di::get(CookieApi::class),
            Di::get('UuidFactoryWithOrderedTimeCodec')
        );
    },
    LogInAction::class => static function () {
        return new LogInAction(
            Di::get(UserApi::class),
            new Response(),
            Di::get(FlashDataApi::class),
            Di::get(DataStore::class)
        );
    },
    LogUserInService::class => static function () {
        return new LogUserInService(
            Di::get(CookieApi::class),
            Di::get(SaveUserService::class),
            Di::get(FetchUserService::class),
            Di::get(EventDispatcher::class),
            Di::get(CreateUserSessionService::class),
            Di::get(ValidateUserPasswordService::class)
        );
    },
    RegisterUserService::class => static function () {
        return new RegisterUserService(
            Di::get(SaveUserService::class),
            Di::get(EventDispatcher::class)
        );
    },
    ResetTokenGarbageCollectionService::class => static function () {
        return new ResetTokenGarbageCollectionService(Di::get(PDO::class));
    },
    SaveUserService::class => static function () {
        return new SaveUserService(
            Di::get(PDO::class),
            Di::get('UuidFactoryWithOrderedTimeCodec'),
            Di::get(EventDispatcher::class)
        );
    },
    SessionGarbageCollectionService::class => static function () {
        return new SessionGarbageCollectionService(Di::get(PDO::class));
    },
    UserApi::class => static function () {
        return new UserApi(new Di(), new DbFactory());
    },
    ValidateUserPasswordService::class => static function () {
        return new ValidateUserPasswordService(
            Di::get(FetchUserService::class)
        );
    },
    GetUserByPasswordResetTokenService::class => static function () {
        return new GetUserByPasswordResetTokenService(
            new OrmFactory(),
            Di::get(FetchUserService::class),
            Di::get('UuidFactoryWithOrderedTimeCodec')
        );
    },
    ResetPasswordByTokenService::class => static function () {
        return new ResetPasswordByTokenService(
            Di::get(PDO::class),
            Di::get(SaveUserService::class),
            Di::get('UuidFactoryWithOrderedTimeCodec'),
            Di::get(GetUserByPasswordResetTokenService::class)
        );
    },
    SetNewPasswordService::class => static function () {
        return new SetNewPasswordService(Di::get(SaveUserService::class));
    },
    UserRecordToModelTransformer::class => static function () {
        return new UserRecordToModelTransformer();
    },
    UserTwigExtension::class => static function () {
        return new UserTwigExtension(Di::get(UserApi::class));
    },
];
