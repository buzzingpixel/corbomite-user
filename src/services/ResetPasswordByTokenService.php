<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use corbomite\db\PDO;
use corbomite\user\exceptions\UserExistsException;
use corbomite\user\exceptions\PasswordTooShortException;
use corbomite\user\exceptions\InvalidUserModelException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\user\exceptions\InvalidResetTokenException;
use corbomite\user\exceptions\InvalidEmailAddressException;

class ResetPasswordByTokenService
{
    private $getUserByPasswordResetToken;
    private $saveUser;
    private $pdo;

    public function __construct(
        GetUserByPasswordResetTokenService $getUserByPasswordResetToken,
        SaveUserService $saveUser,
        PDO $pdo
    ) {
        $this->getUserByPasswordResetToken = $getUserByPasswordResetToken;
        $this->saveUser = $saveUser;
        $this->pdo = $pdo;
    }

    /**
     * @throws InvalidResetTokenException
     * @throws PasswordTooShortException
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function __invoke(string $token, string $password): void
    {
        $this->reset($token, $password);
    }

    /**
     * @throws InvalidResetTokenException
     * @throws PasswordTooShortException
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function reset(string $token, string $password): void
    {
        $getUserByPasswordResetToken = $this->getUserByPasswordResetToken;

        if (! $model = $getUserByPasswordResetToken($token)) {
            throw new InvalidResetTokenException();
        }

        if (\strlen($password) < RegisterUserService::MIN_PASSWORD_LENGTH) {
            throw new PasswordTooShortException();
        }

        $model->passwordHash(password_hash($password, PASSWORD_DEFAULT));

        $saveUser = $this->saveUser;
        $saveUser($model);

        $sql = 'DELETE FROM user_password_reset_tokens WHERE guid = ?';
        $q = $this->pdo->prepare($sql);
        $q->execute([$token]);
    }
}
