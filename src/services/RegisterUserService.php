<?php

declare(strict_types=1);

namespace corbomite\user\services;

use corbomite\events\interfaces\EventDispatcherInterface;
use corbomite\user\events\UserAfterRegisterEvent;
use corbomite\user\events\UserBeforeRegisterEvent;
use corbomite\user\exceptions\InvalidEmailAddressException;
use corbomite\user\exceptions\InvalidUserModelException;
use corbomite\user\exceptions\PasswordTooShortException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\user\exceptions\UserExistsException;
use corbomite\user\models\UserModel;
use const PASSWORD_DEFAULT;
use function mb_strlen;
use function password_hash;

class RegisterUserService
{
    public const MIN_PASSWORD_LENGTH = 8;

    /** @var SaveUserService */
    private $saveUser;
    /** @var EventDispatcherInterface */
    private $dispatcher;

    public function __construct(
        SaveUserService $saveUser,
        EventDispatcherInterface $dispatcher
    ) {
        $this->saveUser   = $saveUser;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws UserExistsException
     * @throws PasswordTooShortException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws InvalidEmailAddressException
     */
    public function __invoke(string $emailAddress, string $password) : void
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
    public function registerUser(string $emailAddress, string $password) : void
    {
        if (mb_strlen($password) < self::MIN_PASSWORD_LENGTH) {
            throw new PasswordTooShortException();
        }

        $model = new UserModel();
        $model->emailAddress($emailAddress);
        $model->passwordHash(password_hash($password, PASSWORD_DEFAULT));

        $this->dispatcher->dispatch(new UserBeforeRegisterEvent($model));

        $this->saveUser->saveUser($model);

        $this->dispatcher->dispatch(new UserAfterRegisterEvent($model));
    }
}
