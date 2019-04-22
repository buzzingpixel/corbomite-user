<?php

declare(strict_types=1);

namespace corbomite\user\services;

use corbomite\db\PDO;
use DateTime;
use DateTimeZone;
use function strtotime;

class SessionGarbageCollectionService
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function __invoke() : void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $dateTime = new DateTime();
        $dateTime->setTimestamp(strtotime('30 days ago'));
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $sql = 'DELETE FROM `user_sessions` WHERE last_touched_at < ?';
        $q   = $this->pdo->prepare($sql);
        $q->execute([$dateTime->format('Y-m-d H:i:s')]);
    }
}
