<?php

declare(strict_types=1);

namespace corbomite\user\services;

use buzzingpixel\cookieapi\CookieApi;
use corbomite\db\Factory as OrmFactory;
use corbomite\user\data\UserSession\UserSession;
use corbomite\user\interfaces\UserModelInterface;
use DateTime;
use DateTimeZone;
use Ramsey\Uuid\UuidFactoryInterface;
use Throwable;
use function time;

class FetchCurrentUserService
{
    /** @var OrmFactory */
    private $ormFactory;
    /** @var CookieApi */
    private $cookieApi;
    /** @var FetchUserService */
    private $fetchUser;
    /** @var UuidFactoryInterface */
    private $uuidFactory;

    public function __construct(
        OrmFactory $ormFactory,
        CookieApi $cookieApi,
        FetchUserService $fetchUser,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->ormFactory  = $ormFactory;
        $this->cookieApi   = $cookieApi;
        $this->fetchUser   = $fetchUser;
        $this->uuidFactory = $uuidFactory;
    }

    public function __invoke() : ?UserModelInterface
    {
        return $this->fetchCurrentUser();
    }

    public function fetchCurrentUser() : ?UserModelInterface
    {
        $cookie = $this->cookieApi->retrieveCookie('user_session_token');

        if (! $cookie) {
            return null;
        }

        $sessionRecord = $this->ormFactory->makeOrm()
            ->select(UserSession::class)
            ->where(
                'guid = ',
                $this->uuidFactory->fromString($cookie->value())->getBytes()
            )
            ->fetchRecord();

        if (! $sessionRecord) {
            return null;
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $lastTouchedAt = new DateTime(
            $sessionRecord->last_touched_at,
            new DateTimeZone($sessionRecord->last_touched_at_time_zone)
        );

        /**
         * We don't want to touch the session (write to the database) every time
         * we fetch the current user. So we'll only do it once every 24 hours
         */
        $h24  = 86400;
        $diff = time() - $lastTouchedAt->getTimestamp();

        if ($diff > $h24) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $dateTime = new DateTime();
            $dateTime->setTimezone(new DateTimeZone('UTC'));
            $sessionRecord->last_touched_at           = $dateTime->format('Y-m-d H:i:s');
            $sessionRecord->last_touched_at_time_zone = $dateTime->getTimezone()
                ->getName();
            $this->ormFactory->makeOrm()->persist($sessionRecord);
        }

        try {
            return $this->fetchUser->fetchUser($sessionRecord->user_guid);
        } catch (Throwable $e) {
            return null;
        }
    }
}
