<?php

declare(strict_types=1);

namespace corbomite\user\services;

use buzzingpixel\cookieapi\CookieApi;
use corbomite\events\interfaces\EventDispatcherInterface;
use corbomite\user\events\UserAfterLogInEvent;
use corbomite\user\events\UserBeforeLogInEvent;
use corbomite\user\exceptions\InvalidEmailAddressException;
use corbomite\user\exceptions\InvalidPasswordException;
use corbomite\user\exceptions\InvalidUserModelException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\user\exceptions\UserExistsException;
use DateTime;
use const PASSWORD_DEFAULT;
use function password_hash;
use function password_needs_rehash;
use function strtotime;

class LogUserInService
{
    /** @var CookieApi */
    private $cookieApi;
    /** @var SaveUserService */
    private $saveUser;
    /** @var FetchUserService */
    private $fetchUser;
    /** @var EventDispatcherInterface */
    private $dispatcher;
    /** @var CreateUserSessionService */
    private $createUserSession;
    /** @var ValidateUserPasswordService */
    private $validateUserPassword;

    public function __construct(
        CookieApi $cookieApi,
        SaveUserService $saveUser,
        FetchUserService $fetchUser,
        EventDispatcherInterface $dispatcher,
        CreateUserSessionService $createUserSession,
        ValidateUserPasswordService $validateUserPassword
    ) {
        $this->cookieApi            = $cookieApi;
        $this->saveUser             = $saveUser;
        $this->fetchUser            = $fetchUser;
        $this->dispatcher           = $dispatcher;
        $this->createUserSession    = $createUserSession;
        $this->validateUserPassword = $validateUserPassword;
    }

    /**
     * @throws UserExistsException
     * @throws InvalidPasswordException
     * @throws UserDoesNotExistException
     * @throws InvalidUserModelException
     * @throws InvalidEmailAddressException
     */
    public function __invoke(string $emailAddress, string $password) : void
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
    public function logUserIn(string $emailAddress, string $password) : void
    {
        if (! $this->validateUserPassword->validateUserPassword($emailAddress, $password)) {
            throw new InvalidPasswordException();
        }

        $user = $this->fetchUser->fetchUser($emailAddress);

        if (! $user) {
            throw new UserDoesNotExistException();
        }

        $this->dispatcher->dispatch(new UserBeforeLogInEvent($user));

        if (password_needs_rehash($user->passwordHash(), PASSWORD_DEFAULT)) {
            $user->passwordHash(password_hash($password, PASSWORD_DEFAULT));
            $this->saveUser->saveUser($user);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $dateTime = new DateTime();
        $dateTime->setTimestamp(strtotime('+ 20 years'));

        $sessionId = $this->createUserSession->createUserSession($user->guid());

        $cookie = $this->cookieApi->makeCookie(
            'user_session_token',
            $sessionId,
            $dateTime
        );

        $this->cookieApi->saveCookie($cookie);

        $this->dispatcher->dispatch(new UserAfterLogInEvent($user));
    }
}
