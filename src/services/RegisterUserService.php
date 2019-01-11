<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use corbomite\user\models\UserModel;
use corbomite\user\exceptions\UserExistsException;
use corbomite\user\exceptions\PasswordTooShortException;
use corbomite\user\exceptions\InvalidUserModelException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\user\exceptions\InvalidEmailAddressException;

class RegisterUserService
{
    public const MIN_PASSWORD_LENGTH = 8;

    private $saveUser;

    public function __construct(SaveUserService $saveUser)
    {
        $this->saveUser = $saveUser;
    }

    /**
     * @throws UserExistsException
     * @throws PasswordTooShortException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws InvalidEmailAddressException
     */
    public function __invoke(string $emailAddress, string $password): void
    {
        $this->registerUser($emailAddress, $password);
    }

    /**
     * @throws UserExistsException
     * @throws PasswordTooShortException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws InvalidEmailAddressException
     */
    public function registerUser(string $emailAddress, string $password): void
    {
        if (\strlen($password) < self::MIN_PASSWORD_LENGTH) {
            throw new PasswordTooShortException();
        }

        $model = new UserModel();
        $model->emailAddress($emailAddress);
        $model->passwordHash(password_hash($password, PASSWORD_DEFAULT));

        $saveUser = $this->saveUser;
        $saveUser($model);
    }
}
