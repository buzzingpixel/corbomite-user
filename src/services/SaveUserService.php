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
use corbomite\user\data\User\User;
use Ramsey\Uuid\UuidFactoryInterface;
use corbomite\db\Factory as OrmFactory;
use corbomite\user\data\User\UserRecord;
use corbomite\user\interfaces\UserModelInterface;
use corbomite\user\exceptions\UserExistsException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\user\exceptions\InvalidUserModelException;
use corbomite\user\exceptions\InvalidEmailAddressException;

class SaveUserService
{
    private $ormFactory;
    private $uuidFactory;

    public function __construct(
        OrmFactory $ormFactory,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->ormFactory = $ormFactory;
        $this->uuidFactory = $uuidFactory;
    }

    /**
     * @throws UserExistsException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws InvalidEmailAddressException
     */
    public function __invoke(UserModelInterface $userModel): void
    {
        $this->saveUser($userModel);
    }

    /**
     * @throws UserExistsException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws InvalidEmailAddressException
     */
    public function saveUser(UserModelInterface $userModel): void
    {
        if (! $userModel->passwordHash() ||
            ! $userModel->emailAddress()
        ) {
            throw new InvalidUserModelException();
        }

        if (! filter_var($userModel->emailAddress(), FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailAddressException();
        }

        if (! $userModel->guid()) {
            $this->saveNewUser($userModel);
            return;
        }

        $this->saveExistingUser($userModel);
    }

    /**
     * @throws UserExistsException
     */
    private function saveNewUser(UserModelInterface $userModel): void
    {
        if ($this->fetchRecord($userModel->emailAddress())) {
            throw new UserExistsException();
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $dateTime = new DateTime();
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $orm = $this->ormFactory->makeOrm();

        $record = $orm->newRecord(User::class);
        /** @noinspection PhpUnhandledExceptionInspection */
        $record->guid = $this->uuidFactory->uuid4()->toString();
        $record->email_address = $userModel->emailAddress();
        $record->password_hash = $userModel->passwordHash();
        $record->user_data = json_encode($userModel->userData());
        $record->added_at = $dateTime->format('Y-m-d H:i:s');
        $record->added_at_time_zone = $dateTime->getTimezone()->getName();

        $orm->persist($record);
    }

    private function fetchRecord(string $emailAddress): ?UserRecord
    {
        return $this->ormFactory->makeOrm()->select(User::class)
            ->where('email_address =', $emailAddress)
            ->fetchRecord();
    }

    /**
     * @throws UserDoesNotExistException
     */
    private function saveExistingUser(UserModelInterface $userModel): void
    {
        if (! $record = $this->fetchRecord($userModel->emailAddress())) {
            throw new UserDoesNotExistException();
        }

        $record->guid = $userModel->guid();
        $record->email_address = $userModel->emailAddress();
        $record->password_hash = $userModel->passwordHash();

        $this->ormFactory->makeOrm()->persist($record);
    }
}
