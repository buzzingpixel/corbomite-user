<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use DateTime;
use Exception;
use DateTimeZone;
use buzzingpixel\cookieapi\CookieApi;
use corbomite\db\Factory as OrmFactory;
use corbomite\user\data\UserSession\UserSession;
use corbomite\user\interfaces\UserModelInterface;

class FetchCurrentUserService
{
    private $ormFactory;
    private $cookieApi;
    private $fetchUser;

    public function __construct(
        OrmFactory $atlas,
        CookieApi $cookieApi,
        FetchUserService $fetchUser
    ) {
        $this->ormFactory = $atlas;
        $this->cookieApi = $cookieApi;
        $this->fetchUser = $fetchUser;
    }

    public function __invoke(): ?UserModelInterface
    {
        return $this->fetchCurrentUser();
    }

    public function fetchCurrentUser(): ?UserModelInterface
    {
        $cookie = $this->cookieApi->retrieveCookie('user_session_token');

        if (! $cookie) {
            return null;
        }

        $sessionRecord = $this->ormFactory->makeOrm()
            ->select(UserSession::class)
            ->where('guid =', $cookie->value())
            ->fetchRecord();

        if (! $sessionRecord) {
            return null;
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $lastTouchedAt = new DateTime(
            $sessionRecord->last_touched_at,
            new DateTimeZone($sessionRecord->last_touched_at_time_zone)
        );

        $h24 = 86400;
        $diff = time() - $lastTouchedAt->getTimestamp();

        if ($diff > $h24) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $dateTime = new DateTime();
            $dateTime->setTimezone(new DateTimeZone('UTC'));
            $sessionRecord->last_touched_at = $dateTime->format('Y-m-d H:i:s');
            $sessionRecord->last_touched_at_time_zone = $dateTime->getTimezone()
                ->getName();
            $this->ormFactory->makeOrm()->persist($sessionRecord);
        }

        try {
            return ($this->fetchUser)($sessionRecord->user_guid);
        } catch (Exception $e) {
            return null;
        }
    }
}
