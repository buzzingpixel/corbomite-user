<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use DateTime;
use DateTimeZone;
use Ramsey\Uuid\UuidFactory;
use corbomite\db\Factory as OrmFactory;
use corbomite\user\data\UserSession\UserSession;
use corbomite\user\exceptions\UserDoesNotExistException;

class CreateUserSessionService
{
    private $uuidFactory;
    private $ormFactory;
    private $fetchUser;

    public function __construct(
        UuidFactory $uuidFactory,
        OrmFactory $atlas,
        FetchUserService $fetchUser
    ) {
        $this->uuidFactory = $uuidFactory;
        $this->ormFactory = $atlas;
        $this->fetchUser = $fetchUser;
    }

    /**
     * @throws UserDoesNotExistException
     */
    public function __invoke(string $userGuid): string
    {
        return $this->createUserSession($userGuid);
    }

    /**
     * @throws UserDoesNotExistException
     */
    public function createUserSession(string $userGuid): string
    {
        $fetchUser = $this->fetchUser;
        if (! $fetchUser($userGuid)) {
            throw new UserDoesNotExistException();
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $dateTime = new DateTime();
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $ormFactory = $this->ormFactory->makeOrm();

        $record = $ormFactory->newRecord(UserSession::class);
        /** @noinspection PhpUnhandledExceptionInspection */
        $record->guid = $this->uuidFactory->uuid4()->toString();
        $record->user_guid = $userGuid;
        $record->added_at = $dateTime->format('Y-m-d H:i:s');
        $record->added_at_time_zone = $dateTime->getTimezone()->getName();
        $record->last_touched_at = $dateTime->format('Y-m-d H:i:s');
        $record->last_touched_at_time_zone = $dateTime->getTimezone()->getName();

        $ormFactory->persist($record);

        return $record->guid;
    }
}
