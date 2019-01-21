<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use corbomite\user\models\UserModel;
use corbomite\user\events\UserAfterRegisterEvent;
use corbomite\user\events\UserBeforeRegisterEvent;
use corbomite\user\exceptions\UserExistsException;
use corbomite\user\exceptions\PasswordTooShortException;
use corbomite\user\exceptions\InvalidUserModelException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\events\interfaces\EventDispatcherInterface;
use corbomite\user\exceptions\InvalidEmailAddressException;

class RegisterUserService
{
    public const MIN_PASSWORD_LENGTH = 8;

    private $saveUser;
    private $dispatcher;

    public function __construct(
        SaveUserService $saveUser,
        EventDispatcherInterface $dispatcher
    ) {
        $this->saveUser = $saveUser;
        $this->dispatcher = $dispatcher;
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

        $before = new UserBeforeRegisterEvent($model);

        $this->dispatcher->dispatch($before->provider(), $before->name(), $before);

        $this->saveUser->saveUser($model);

        $after = new UserAfterRegisterEvent($model);

        $this->dispatcher->dispatch($after->name(), $after->name(), $after);
    }
}
