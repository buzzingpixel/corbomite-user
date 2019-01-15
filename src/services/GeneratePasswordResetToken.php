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
use corbomite\user\interfaces\UserModelInterface;
use corbomite\user\data\UserPasswordResetToken\UserPasswordResetToken;

class GeneratePasswordResetToken
{
    private $uuidFactory;
    private $ormFactory;

    public function __construct(
        UuidFactory $uuidFactory,
        OrmFactory $ormFactory
    ) {
        $this->uuidFactory = $uuidFactory;
        $this->ormFactory = $ormFactory;
    }

    public function __invoke(UserModelInterface $userModel): string
    {
        return $this->generate($userModel);
    }

    public function generate(UserModelInterface $userModel): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $dateTime = new DateTime();
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $orm = $this->ormFactory->makeOrm();

        /** @noinspection PhpUnhandledExceptionInspection */
        $token = $this->uuidFactory->uuid4()->toString();

        $record = $orm->newRecord(UserPasswordResetToken::class);
        /** @noinspection PhpUnhandledExceptionInspection */
        $record->guid = $token;
        $record->user_guid = $userModel->guid();

        $record->added_at = $dateTime->format('Y-m-d H:i:s');
        $record->added_at_time_zone = $dateTime->getTimezone()->getName();

        $orm->persist($record);

        return $token;
    }
}
