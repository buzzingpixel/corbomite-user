<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user;

use corbomite\di\Di;
use corbomite\db\Factory as DbFactory;
use corbomite\user\services\SaveUserService;
use corbomite\user\services\FetchUserService;
use corbomite\user\services\LogUserInService;
use corbomite\user\services\FetchUsersService;
use corbomite\user\interfaces\UserApiInterface;
use corbomite\db\interfaces\QueryModelInterface;
use corbomite\user\services\RegisterUserService;
use corbomite\user\interfaces\UserModelInterface;
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

class UserApi implements UserApiInterface
{
    private $di;
    private $dbFactory;

    public function __construct(Di $di, DbFactory $dbFactory)
    {
        $this->di = $di;
        $this->dbFactory = $dbFactory;
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
        $service->registerUser($emailAddress, $password);
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function saveUser(UserModelInterface $user): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(SaveUserService::class);
        $service->saveUser($user);
    }

    public function fetchUser(string $identifier): ?UserModelInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(FetchUserService::class);
        return $service->fetchUser($identifier);
    }

    public function makeQueryModel(): QueryModelInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->dbFactory->makeQueryModel();
    }

    public function fetchCurrentUser(): ?UserModelInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(FetchCurrentUserService::class);
        return $service();
    }

    public function fetchOne(?QueryModelInterface $queryModel = null): ?UserModelInterface
    {
        if (! $queryModel) {
            $queryModel = $this->makeQueryModel();
            $queryModel->addOrder('email_address', 'asc');
        }

        $queryModel->limit(1);

        return $this->fetchAll($queryModel)[0] ?? null;
    }

    public function fetchAll(?QueryModelInterface $queryModel = null): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(FetchUsersService::class);

        if (! $queryModel) {
            $queryModel = $this->makeQueryModel();
            $queryModel->addOrder('email_address', 'asc');
        }

        return $service->fetch($queryModel);
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
        return $service->validateUserPassword($identifier, $password);
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
        $service->logUserIn($emailAddress, $password);
    }

    public function logCurrentUserOut(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(LogCurrentUserOutService::class);
        $service->logCurrentUserOut();
    }

    public function generatePasswordResetToken(UserModelInterface $user): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(GeneratePasswordResetToken::class);
        return $service->generate($user);
    }

    public function getUserByPasswordResetToken(string $token): ?UserModelInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(GetUserByPasswordResetTokenService::class);
        return $service->get($token);
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
        $service->reset($token, $password);
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws PasswordTooShortException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function setNewPassword(UserModelInterface $user, string $password): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(SetNewPasswordService::class);
        $service->set($user, $password);
    }
}
