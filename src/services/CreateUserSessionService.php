<?php

declare(strict_types=1);

namespace corbomite\user\services;

use corbomite\db\Factory as OrmFactory;
use corbomite\user\data\UserSession\UserSession;
use corbomite\user\exceptions\UserDoesNotExistException;
use DateTime;
use DateTimeZone;
use Ramsey\Uuid\UuidFactoryInterface;
use function preg_match;

class CreateUserSessionService
{
    /** @var OrmFactory */
    private $ormFactory;
    /** @var FetchUserService */
    private $fetchUser;
    /** @var UuidFactoryInterface */
    private $uuidFactory;

    public function __construct(
        OrmFactory $ormFactory,
        FetchUserService $fetchUser,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->ormFactory  = $ormFactory;
        $this->fetchUser   = $fetchUser;
        $this->uuidFactory = $uuidFactory;
    }

    /**
     * @throws UserDoesNotExistException
     */
    public function __invoke(string $userGuid) : string
    {
        return $this->createUserSession($userGuid);
    }

    /**
     * @throws UserDoesNotExistException
     */
    public function createUserSession(string $userGuid) : string
    {
        if (! $this->fetchUser->fetchUser($userGuid)) {
            throw new UserDoesNotExistException();
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $dateTime = new DateTime();
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $ormFactory = $this->ormFactory->makeOrm();

        if (! $this->isBinary($userGuid)) {
            $userGuid = $this->uuidFactory->fromString($userGuid)->getBytes();
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $sessionGuid = $this->uuidFactory->uuid1();

        $record                            = $ormFactory->newRecord(UserSession::class);
        $record->guid                      = $sessionGuid->getBytes();
        $record->user_guid                 = $userGuid;
        $record->added_at                  = $dateTime->format('Y-m-d H:i:s');
        $record->added_at_time_zone        = $dateTime->getTimezone()->getName();
        $record->last_touched_at           = $dateTime->format('Y-m-d H:i:s');
        $record->last_touched_at_time_zone = $dateTime->getTimezone()->getName();

        $ormFactory->persist($record);

        return $sessionGuid->toString();
    }

    /**
     * @param mixed $str
     */
    private function isBinary($str) : bool
    {
        return preg_match('~[^\x20-\x7E\t\r\n]~', $str) > 0;
    }
}
