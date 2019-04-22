<?php

declare(strict_types=1);

use buzzingpixel\cookieapi\CookieApi;
use Composer\Autoload\ClassLoader;
use corbomite\cli\services\CliQuestionService;
use corbomite\db\Factory as DbFactory;
use corbomite\db\Factory as OrmFactory;
use corbomite\db\PDO;
use corbomite\db\services\BuildQueryService;
use corbomite\events\EventDispatcher;
use corbomite\flashdata\FlashDataApi;
use corbomite\requestdatastore\DataStore;
use corbomite\user\actions\CreateMigrationsAction;
use corbomite\user\actions\CreateUserAction;
use corbomite\user\http\actions\LogInAction;
use corbomite\user\interfaces\UserApiInterface;
use corbomite\user\interfaces\UserRecordToModelTransformerInterface;
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
use Psr\Container\ContainerInterface;
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
    CreateUserAction::class => static function (ContainerInterface $di) {
        return new CreateUserAction(
            $di->get(UserApi::class),
            new ConsoleOutput(),
            $di->get(CliQuestionService::class)
        );
    },
    CreateUserSessionService::class => static function (ContainerInterface $di) {
        return new CreateUserSessionService(
            new OrmFactory(),
            $di->get(FetchUserService::class),
            $di->get('UuidFactoryWithOrderedTimeCodec')
        );
    },
    DeleteUserService::class => static function (ContainerInterface $di) {
        return new DeleteUserService(
            $di->get(PDO::class),
            $di->get(EventDispatcher::class)
        );
    },
    FetchCurrentUserService::class => static function (ContainerInterface $di) {
        return new FetchCurrentUserService(
            new OrmFactory(),
            $di->get(CookieApi::class),
            $di->get(FetchUserService::class),
            $di->get('UuidFactoryWithOrderedTimeCodec')
        );
    },
    FetchUserService::class => static function (ContainerInterface $di) {
        return new FetchUserService(
            new DbFactory(),
            $di->get(FetchUsersService::class),
            $di->get('UuidFactoryWithOrderedTimeCodec')
        );
    },
    FetchUsersService::class => static function (ContainerInterface $di) {
        return new FetchUsersService(
            $di->get(PDO::class),
            $di->get(BuildQueryService::class),
            $di->get(UserRecordToModelTransformer::class)
        );
    },
    GeneratePasswordResetToken::class => static function (ContainerInterface $di) {
        return new GeneratePasswordResetToken(
            new OrmFactory(),
            $di->get('UuidFactoryWithOrderedTimeCodec')
        );
    },
    LogCurrentUserOutService::class => static function (ContainerInterface $di) {
        return new LogCurrentUserOutService(
            new OrmFactory(),
            $di->get(CookieApi::class),
            $di->get('UuidFactoryWithOrderedTimeCodec')
        );
    },
    LogInAction::class => static function (ContainerInterface $di) {
        return new LogInAction(
            $di->get(UserApi::class),
            new Response(),
            $di->get(FlashDataApi::class),
            $di->get(DataStore::class)
        );
    },
    LogUserInService::class => static function (ContainerInterface $di) {
        return new LogUserInService(
            $di->get(CookieApi::class),
            $di->get(SaveUserService::class),
            $di->get(FetchUserService::class),
            $di->get(EventDispatcher::class),
            $di->get(CreateUserSessionService::class),
            $di->get(ValidateUserPasswordService::class)
        );
    },
    RegisterUserService::class => static function (ContainerInterface $di) {
        return new RegisterUserService(
            $di->get(SaveUserService::class),
            $di->get(EventDispatcher::class)
        );
    },
    ResetTokenGarbageCollectionService::class => static function (ContainerInterface $di) {
        return new ResetTokenGarbageCollectionService($di->get(PDO::class));
    },
    SaveUserService::class => static function (ContainerInterface $di) {
        return new SaveUserService(
            $di->get(PDO::class),
            $di->get('UuidFactoryWithOrderedTimeCodec'),
            $di->get(EventDispatcher::class)
        );
    },
    SessionGarbageCollectionService::class => static function (ContainerInterface $di) {
        return new SessionGarbageCollectionService($di->get(PDO::class));
    },
    UserApi::class => static function (ContainerInterface $di) {
        return new UserApi($di, new DbFactory());
    },
    UserApiInterface::class => static function (ContainerInterface $di) {
        return $di->get(UserApi::class);
    },
    UserRecordToModelTransformer::class => static function (ContainerInterface $di) {
        return new UserRecordToModelTransformer();
    },
    UserRecordToModelTransformerInterface::class => static function (ContainerInterface $di) {
        return $di->get(UserRecordToModelTransformer::class);
    },
    UserTwigExtension::class => static function (ContainerInterface $di) {
        return new UserTwigExtension($di->get(UserApi::class));
    },
    ValidateUserPasswordService::class => static function (ContainerInterface $di) {
        return new ValidateUserPasswordService(
            $di->get(FetchUserService::class)
        );
    },
    GetUserByPasswordResetTokenService::class => static function (ContainerInterface $di) {
        return new GetUserByPasswordResetTokenService(
            new OrmFactory(),
            $di->get(FetchUserService::class),
            $di->get('UuidFactoryWithOrderedTimeCodec')
        );
    },
    ResetPasswordByTokenService::class => static function (ContainerInterface $di) {
        return new ResetPasswordByTokenService(
            $di->get(PDO::class),
            $di->get(SaveUserService::class),
            $di->get('UuidFactoryWithOrderedTimeCodec'),
            $di->get(GetUserByPasswordResetTokenService::class)
        );
    },
    SetNewPasswordService::class => static function (ContainerInterface $di) {
        return new SetNewPasswordService($di->get(SaveUserService::class));
    },
];
