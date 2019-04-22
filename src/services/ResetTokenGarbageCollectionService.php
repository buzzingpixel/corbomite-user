<?php

declare(strict_types=1);

namespace corbomite\user\services;

use corbomite\db\PDO;
use DateTime;
use DateTimeZone;
use function strtotime;

class ResetTokenGarbageCollectionService
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
        $dateTime->setTimestamp(strtotime('2 hours ago'));
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $sql = 'DELETE FROM user_password_reset_tokens WHERE added_at < ?';
        $q   = $this->pdo->prepare($sql);
        $q->execute([$dateTime->format('Y-m-d H:i:s')]);
    }
}
