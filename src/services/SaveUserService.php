<?php

declare(strict_types=1);

namespace corbomite\user\services;

use corbomite\events\interfaces\EventDispatcherInterface;
use corbomite\user\events\UserAfterSaveEvent;
use corbomite\user\events\UserBeforeSaveEvent;
use corbomite\user\exceptions\InvalidEmailAddressException;
use corbomite\user\exceptions\InvalidUserModelException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\user\exceptions\UserExistsException;
use corbomite\user\interfaces\UserModelInterface;
use DateTime;
use DateTimeZone;
use PDO;
use Ramsey\Uuid\UuidFactoryInterface;
use const FILTER_VALIDATE_EMAIL;
use function array_merge;
use function filter_var;
use function json_encode;

class SaveUserService
{
    /** @var PDO */
    private $pdo;
    /** @var EventDispatcherInterface */
    private $dispatcher;
    /** @var UuidFactoryInterface */
    private $uuidFactory;

    public function __construct(
        PDO $pdo,
        UuidFactoryInterface $uuidFactory,
        EventDispatcherInterface $dispatcher
    ) {
        $this->pdo         = $pdo;
        $this->dispatcher  = $dispatcher;
        $this->uuidFactory = $uuidFactory;
    }

    /**
     * @throws UserExistsException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws InvalidEmailAddressException
     */
    public function __invoke(UserModelInterface $model) : void
    {
        $this->saveUser($model);
    }

    /**
     * @throws UserExistsException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws InvalidEmailAddressException
     */
    public function saveUser(UserModelInterface $model) : void
    {
        if (! $model->passwordHash() ||
            ! $model->emailAddress()
        ) {
            throw new InvalidUserModelException();
        }

        if (! filter_var($model->emailAddress(), FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailAddressException();
        }

        if (! $this->checkIfGuidExists($model->getGuidAsBytes())) {
            $this->dispatcher->dispatch(new UserBeforeSaveEvent($model, true));

            $this->saveNewUser($model);

            $this->dispatcher->dispatch(new UserAfterSaveEvent($model, true));

            return;
        }

        $this->dispatcher->dispatch(new UserBeforeSaveEvent($model, false));

        $this->saveExistingUser($model);

        $this->dispatcher->dispatch(new UserAfterSaveEvent($model, false));
    }

    /**
     * @throws UserExistsException
     */
    private function saveNewUser(UserModelInterface $model) : void
    {
        if ($this->checkIfEmailRegistered($model->emailAddress())) {
            throw new UserExistsException();
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $dateTime = new DateTime();
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $into   = 'guid, email_address, password_hash, user_data, added_at, added_at_time_zone';
        $values = ':guid, :email_address, :password_hash, :user_data, :added_at, :added_at_time_zone';

        /** @noinspection PhpUnhandledExceptionInspection */
        $guid = $this->uuidFactory->uuid1();

        $bind = [
            ':guid' => '',
            ':email_address' => '',
            ':password_hash' => '',
            ':user_data' => '',
            ':added_at' => '',
            ':added_at_time_zone' => '',
        ];

        foreach ($model->extendedProperties() as $key => $val) {
            $into            .= ', ' . $key;
            $values          .= ', :' . $key;
            $bind[':' . $key] = $val;
        }

        $bind = array_merge($bind, [
            ':guid' => $guid->getBytes(),
            ':email_address' => $model->emailAddress(),
            ':password_hash' => $model->passwordHash(),
            ':user_data' => json_encode($model->userData()),
            ':added_at' => $dateTime->format('Y-m-d H:i:s'),
            ':added_at_time_zone' => $dateTime->getTimezone()->getName(),
        ]);

        $statement = $this->pdo->prepare(
            'INSERT INTO `users` (' . $into . ') VALUES (' . $values . ')'
        );

        $statement->execute($bind);
    }

    /**
     * @throws UserDoesNotExistException
     */
    private function saveExistingUser(UserModelInterface $model) : void
    {
        if (! $this->checkIfGuidExists($model->getGuidAsBytes())) {
            throw new UserDoesNotExistException();
        }

        $bind = [
            ':email_address' => '',
            ':password_hash' => '',
            ':user_data' => '',
        ];

        $sql  = 'UPDATE `users` SET';
        $sql .= ' email_address=:email_address';
        $sql .= ', password_hash=:password_hash';
        $sql .= ', user_data=:user_data';

        foreach ($model->extendedProperties() as $key => $val) {
            $sql             .= ', ' . $key . '=:' . $key;
            $bind[':' . $key] = $val;
        }

        $sql .= ' WHERE guid=:guid_where';

        $bind = array_merge($bind, [
            ':email_address' => $model->emailAddress(),
            ':password_hash' => $model->passwordHash(),
            ':user_data' => json_encode($model->userData()),
            ':guid_where' => $model->getGuidAsBytes(),
        ]);

        $statement = $this->pdo->prepare($sql);

        $statement->execute($bind);
    }

    private function checkIfEmailRegistered(string $emailAddress) : bool
    {
        $query = $this->pdo->prepare(
            'SELECT COUNT(*) as total FROM `users` WHERE `email_address` = :email'
        );

        $query->execute([':email' => $emailAddress]);

        return $query->fetch(PDO::FETCH_OBJ)->total > 0;
    }

    private function checkIfGuidExists(string $guid) : bool
    {
        $query = $this->pdo->prepare(
            'SELECT COUNT(*) as total FROM `users` WHERE `guid` = :guid'
        );

        $query->execute([':guid' => $guid]);

        return $query->fetch(PDO::FETCH_OBJ)->total > 0;
    }
}
