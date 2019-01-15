<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use corbomite\user\data\User\User;
use corbomite\db\Factory as OrmFactory;
use corbomite\user\data\User\UserRecord;
use corbomite\user\interfaces\UserModelInterface;
use corbomite\user\interfaces\UserRecordToModelTransformerInterface;

class FetchUserService
{
    private $ormFactory;
    private $userRecordToModel;

    public function __construct(
        OrmFactory $ormFactory,
        UserRecordToModelTransformerInterface $userRecordToModel
    ) {
        $this->ormFactory = $ormFactory;
        $this->userRecordToModel = $userRecordToModel;
    }

    public function __invoke(string $identifier): ?UserModelInterface
    {
        return $this->fetchUser($identifier);
    }

    private $storedUsersByEmail = [];
    private $storedUsersByIdentifier = [];

    public function fetchUser(
        string $identifier,
        $bypassCache = false
    ): ?UserModelInterface {
        if (! $bypassCache &&
            isset($this->storedUsersByEmail[$identifier])
        ) {
            return $this->storedUsersByEmail[$identifier];
        }

        if (! $bypassCache &&
            isset($this->storedUsersByIdentifier[$identifier])
        ) {
            return $this->storedUsersByIdentifier[$identifier];
        }

        $record = $this->fetchRecord($identifier);

        if (! $record) {
            return null;
        }

        $model = $this->userRecordToModel->transform($record);

        $this->storedUsersByEmail[$model->emailAddress()] = $model;
        $this->storedUsersByIdentifier[$model->guid()] = $model;

        return $model;
    }

    private function fetchRecord(string $identifier): ?UserRecord
    {
        return $this->ormFactory->makeOrm()->select(User::class)
            ->where('email_address =', $identifier)
            ->orWhere('guid =', $identifier)
            ->fetchRecord();
    }
}
