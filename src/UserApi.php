<?php

declare(strict_types=1);

namespace corbomite\user;

use corbomite\db\Factory as DbFactory;
use corbomite\db\interfaces\QueryModelInterface;
use corbomite\user\exceptions\InvalidEmailAddressException;
use corbomite\user\exceptions\InvalidPasswordException;
use corbomite\user\exceptions\InvalidResetTokenException;
use corbomite\user\exceptions\InvalidUserModelException;
use corbomite\user\exceptions\PasswordTooShortException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\user\exceptions\UserExistsException;
use corbomite\user\interfaces\UserApiInterface;
use corbomite\user\interfaces\UserModelInterface;
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
use corbomite\user\services\SaveUserService;
use corbomite\user\services\SetNewPasswordService;
use corbomite\user\services\ValidateUserPasswordService;
use Psr\Container\ContainerInterface;

class UserApi implements UserApiInterface
{
    /** @var ContainerInterface */
    private $di;
    /** @var DbFactory */
    private $dbFactory;

    public function __construct(ContainerInterface $di, DbFactory $dbFactory)
    {
        $this->di        = $di;
        $this->dbFactory = $dbFactory;
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws PasswordTooShortException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function registerUser(string $emailAddress, string $password) : void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(RegisterUserService::class);
        $service->registerUser($emailAddress, $password);
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function saveUser(UserModelInterface $user) : void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(SaveUserService::class);
        $service->saveUser($user);
    }

    public function fetchUser(string $identifier) : ?UserModelInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(FetchUserService::class);

        return $service->fetchUser($identifier);
    }

    public function makeQueryModel() : QueryModelInterface
    {
        return $this->dbFactory->makeQueryModel();
    }

    public function fetchCurrentUser() : ?UserModelInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(FetchCurrentUserService::class);

        return $service();
    }

    public function fetchOne(?QueryModelInterface $queryModel = null) : ?UserModelInterface
    {
        if (! $queryModel) {
            $queryModel = $this->makeQueryModel();
            $queryModel->addOrder('email_address', 'asc');
        }

        $queryModel->limit(1);

        return $this->fetchAll($queryModel)[0] ?? null;
    }

    /**
     * @return UserModelInterface[]
     */
    public function fetchAll(?QueryModelInterface $queryModel = null) : array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(FetchUsersService::class);

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
    ) : bool {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(ValidateUserPasswordService::class);

        return $service->validateUserPassword($identifier, $password);
    }

    /**
     * @throws UserExistsException
     * @throws InvalidPasswordException
     * @throws UserDoesNotExistException
     * @throws InvalidUserModelException
     * @throws InvalidEmailAddressException
     */
    public function logUserIn(string $emailAddress, string $password) : void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(LogUserInService::class);
        $service->logUserIn($emailAddress, $password);
    }

    public function logCurrentUserOut() : void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(LogCurrentUserOutService::class);
        $service->logCurrentUserOut();
    }

    public function generatePasswordResetToken(UserModelInterface $user) : string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(GeneratePasswordResetToken::class);

        return $service->generate($user);
    }

    public function getUserByPasswordResetToken(string $token) : ?UserModelInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(GetUserByPasswordResetTokenService::class);

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
    public function resetPasswordByToken(string $token, string $password) : void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(ResetPasswordByTokenService::class);
        $service->reset($token, $password);
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws PasswordTooShortException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function setNewPassword(UserModelInterface $user, string $password) : void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(SetNewPasswordService::class);
        $service->set($user, $password);
    }

    public function deleteUser(UserModelInterface $user) : void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(DeleteUserService::class);
        $service->delete($user);
    }
}
