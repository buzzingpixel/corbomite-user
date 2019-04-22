<?php

declare(strict_types=1);

namespace corbomite\user\services;

use corbomite\user\exceptions\UserDoesNotExistException;
use function password_verify;

class ValidateUserPasswordService
{
    /** @var FetchUserService */
    private $fetchUser;

    public function __construct(FetchUserService $fetchUser)
    {
        $this->fetchUser = $fetchUser;
    }

    /**
     * @throws UserDoesNotExistException
     */
    public function __invoke(string $identifier, string $password) : bool
    {
        return $this->validateUserPassword($identifier, $password);
    }

    /**
     * @throws UserDoesNotExistException
     */
    public function validateUserPassword(
        string $identifier,
        string $password
    ) : bool {
        $user = $this->fetchUser->fetchUser($identifier);

        if (! $user) {
            throw new UserDoesNotExistException();
        }

        return password_verify($password, $user->passwordHash());
    }
}
