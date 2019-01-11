<?php
declare(strict_types=1);

namespace corbomite\user;

use corbomite\di\Di;
use corbomite\user\models\UserModel;
use corbomite\user\services\SaveUserService;
use corbomite\user\services\FetchUserService;
use corbomite\user\services\LogUserInService;
use corbomite\user\services\RegisterUserService;
use corbomite\user\exceptions\UserExistsException;
use corbomite\user\services\FetchCurrentUserService;
use corbomite\user\services\LogCurrentUserOutService;
use corbomite\user\exceptions\InvalidPasswordException;
use corbomite\user\exceptions\PasswordTooShortException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\user\exceptions\InvalidUserModelException;
use corbomite\user\services\ValidateUserPasswordService;
use corbomite\user\exceptions\InvalidEmailAddressException;

class UserApi
{
    private $di;

    public function __construct(Di $di)
    {
        $this->di = $di;
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws PasswordTooShortException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function registerUser(string $emailAddress, string $password): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(RegisterUserService::class);
        $service($emailAddress, $password);
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function saveUser(UserModel $user): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(SaveUserService::class);
        $service($user);
    }

    public function fetchUser(string $identifier): ?UserModel
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(FetchUserService::class);
        return $service($identifier);
    }

    public function fetchCurrentUser(): ?UserModel
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(FetchCurrentUserService::class);
        return $service();
    }

    /**
     * @throws UserDoesNotExistException
     */
    public function validateUserPassword(
        string $identifier,
        string $password
    ): bool {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(
            ValidateUserPasswordService::class
        );
        return $service($identifier, $password);
    }

    /**
     * @throws UserExistsException
     * @throws InvalidPasswordException
     * @throws UserDoesNotExistException
     * @throws InvalidUserModelException
     * @throws InvalidEmailAddressException
     */
    public function logUserIn(string $emailAddress, string $password): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(LogUserInService::class);
        $service($emailAddress, $password);
    }

    public function logCurrentUserOut(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(LogCurrentUserOutService::class);
        $service();
    }
}
