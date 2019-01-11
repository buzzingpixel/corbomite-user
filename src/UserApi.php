<?php
declare(strict_types=1);

namespace corbomite\user;

use corbomite\di\Di;
use corbomite\user\models\UserModel;
use corbomite\user\services\SaveUserService;
use corbomite\user\exceptions\UserExistsException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\user\exceptions\InvalidUserModelException;
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
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function saveUser(UserModel $user): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(SaveUserService::class);
        $service($user);
    }
}
