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
use corbomite\user\services\SetNewPasswordService;
use corbomite\user\services\FetchCurrentUserService;
use corbomite\user\services\LogCurrentUserOutService;
use corbomite\user\exceptions\InvalidPasswordException;
use corbomite\user\services\GeneratePasswordResetToken;
use corbomite\user\exceptions\PasswordTooShortException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\user\exceptions\InvalidUserModelException;
use corbomite\user\services\ValidateUserPasswordService;
use corbomite\user\services\ResetPasswordByTokenService;
use corbomite\user\exceptions\InvalidResetTokenException;
use corbomite\user\exceptions\InvalidEmailAddressException;
use corbomite\user\services\GetUserByPasswordResetTokenService;

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

    public function generatePasswordResetToken(UserModel $user): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(GeneratePasswordResetToken::class);
        return $service($user);
    }

    public function getUserByPasswordResetToken(string $token): ?UserModel
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(GetUserByPasswordResetTokenService::class);
        return $service($token);
    }

    /**
     * @throws InvalidResetTokenException
     * @throws PasswordTooShortException
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function resetPasswordByToken(string $token, string $password): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(ResetPasswordByTokenService::class);
        $service($token, $password);
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws PasswordTooShortException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function setNewPassword(UserModel $user, string $password): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(SetNewPasswordService::class);
        $service($user, $password);
    }
}
