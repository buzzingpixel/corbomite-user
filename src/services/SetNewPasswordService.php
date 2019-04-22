<?php

declare(strict_types=1);

namespace corbomite\user\services;

use corbomite\user\exceptions\InvalidEmailAddressException;
use corbomite\user\exceptions\InvalidUserModelException;
use corbomite\user\exceptions\PasswordTooShortException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\user\exceptions\UserExistsException;
use corbomite\user\interfaces\UserModelInterface;
use const PASSWORD_DEFAULT;
use function mb_strlen;
use function password_hash;

class SetNewPasswordService
{
    /** @var SaveUserService */
    private $saveUser;

    public function __construct(SaveUserService $saveUser)
    {
        $this->saveUser = $saveUser;
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws PasswordTooShortException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function __invoke(UserModelInterface $model, string $password) : void
    {
        $this->set($model, $password);
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws PasswordTooShortException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function set(UserModelInterface $model, string $password) : void
    {
        if (mb_strlen($password) < RegisterUserService::MIN_PASSWORD_LENGTH) {
            throw new PasswordTooShortException();
        }

        $model->passwordHash(password_hash($password, PASSWORD_DEFAULT));

        $this->saveUser->saveUser($model);
    }
}
