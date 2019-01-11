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
use corbomite\db\PDO;

class ResetTokenGarbageCollectionService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function __invoke()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $dateTime = new DateTime();
        $dateTime->setTimestamp(strtotime('2 hours ago'));
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $sql = 'DELETE FROM user_password_reset_tokens WHERE added_at < ?';
        $q = $this->pdo->prepare($sql);
        $q->execute([$dateTime->format('Y-m-d H:i:s')]);
    }
}
