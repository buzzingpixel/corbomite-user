<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use corbomite\user\exceptions\UserDoesNotExistException;

class ValidateUserPasswordService
{
    private $fetchUser;

    public function __construct(FetchUserService $fetchUser)
    {
        $this->fetchUser = $fetchUser;
    }

    /**
     * @throws UserDoesNotExistException
     */
    public function __invoke(string $identifier, string $password): bool
    {
        return $this->validateUserPassword($identifier, $password);
    }

    /**
     * @throws UserDoesNotExistException
     */
    public function validateUserPassword(
        string $identifier,
        string $password
    ): bool {
        $fetchUser = $this->fetchUser;

        if (! $user = $fetchUser($identifier)) {
            throw new UserDoesNotExistException();
        }

        return password_verify($password, $user->passwordHash());
    }
}
