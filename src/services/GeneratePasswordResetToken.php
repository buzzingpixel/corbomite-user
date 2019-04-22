<?php

declare(strict_types=1);

namespace corbomite\user\services;

use corbomite\db\Factory as OrmFactory;
use corbomite\user\data\UserPasswordResetToken\UserPasswordResetToken;
use corbomite\user\interfaces\UserModelInterface;
use DateTime;
use DateTimeZone;
use Ramsey\Uuid\UuidFactory;

class GeneratePasswordResetToken
{
    /** @var OrmFactory */
    private $ormFactory;
    /** @var UuidFactory */
    private $uuidFactory;

    public function __construct(
        OrmFactory $ormFactory,
        UuidFactory $uuidFactory
    ) {
        $this->ormFactory  = $ormFactory;
        $this->uuidFactory = $uuidFactory;
    }

    public function __invoke(UserModelInterface $userModel) : string
    {
        return $this->generate($userModel);
    }

    public function generate(UserModelInterface $userModel) : string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $dateTime = new DateTime();
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $orm = $this->ormFactory->makeOrm();

        /** @noinspection PhpUnhandledExceptionInspection */
        $token = $this->uuidFactory->uuid4();

        $record = $orm->newRecord(UserPasswordResetToken::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $record->guid      = $token->getBytes();
        $record->user_guid = $userModel->getGuidAsBytes();

        $record->added_at           = $dateTime->format('Y-m-d H:i:s');
        $record->added_at_time_zone = $dateTime->getTimezone()->getName();

        $orm->persist($record);

        return $token->toString();
    }
}
