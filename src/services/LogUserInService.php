<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use DateTime;
use buzzingpixel\cookieapi\CookieApi;
use corbomite\user\events\UserAfterLogInEvent;
use corbomite\user\events\UserBeforeLogInEvent;
use corbomite\user\exceptions\UserExistsException;
use corbomite\user\exceptions\InvalidPasswordException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\user\exceptions\InvalidUserModelException;
use corbomite\events\interfaces\EventDispatcherInterface;
use corbomite\user\exceptions\InvalidEmailAddressException;

class LogUserInService
{
    private $validateUserPassword;
    private $fetchUser;
    private $saveUser;
    private $createUserSession;
    private $cookieApi;
    private $dispatcher;

    public function __construct(
        ValidateUserPasswordService $validateUserPassword,
        FetchUserService $fetchUser,
        SaveUserService $saveUser,
        CreateUserSessionService $createUserSession,
        CookieApi $cookieApi,
        EventDispatcherInterface $dispatcher
    ) {
        $this->validateUserPassword = $validateUserPassword;
        $this->fetchUser = $fetchUser;
        $this->saveUser = $saveUser;
        $this->createUserSession = $createUserSession;
        $this->cookieApi = $cookieApi;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws UserExistsException
     * @throws InvalidPasswordException
     * @throws UserDoesNotExistException
     * @throws InvalidUserModelException
     * @throws InvalidEmailAddressException
     */
    public function __invoke(string $emailAddress, string $password): void
    {
        $this->logUserIn($emailAddress, $password);
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
        if (! $this->validateUserPassword->validateUserPassword($emailAddress, $password)) {
            throw new InvalidPasswordException();
        }

        $user = $this->fetchUser->fetchUser($emailAddress);

        if (! $user) {
            throw new UserDoesNotExistException();
        }

        $before = new UserBeforeLogInEvent($user);

        $this->dispatcher->dispatch($before->provider(), $before->name(), $before);

        if (password_needs_rehash($user->passwordHash(), PASSWORD_DEFAULT)) {
            $user->passwordHash(password_hash($password, PASSWORD_DEFAULT));
            $this->saveUser->saveUser($user);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $dateTime = new DateTime();
        $dateTime->setTimestamp(strtotime('+ 20 years'));

        $createUserSession = $this->createUserSession;
        $sessionId = $createUserSession($user->guid());

        $cookie = $this->cookieApi->makeCookie(
            'user_session_token',
            $sessionId,
            $dateTime
        );

        $this->cookieApi->saveCookie($cookie);

        $after = new UserAfterLogInEvent($user);

        $this->dispatcher->dispatch($after->provider(), $after->name(), $after);
    }
}
