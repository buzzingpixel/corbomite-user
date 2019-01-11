<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use corbomite\user\models\UserModel;
use corbomite\db\Factory as OrmFactory;
use corbomite\user\data\UserPasswordResetToken\UserPasswordResetToken;
use corbomite\user\data\UserPasswordResetToken\UserPasswordResetTokenRecord;

class GetUserByPasswordResetToken
{
    private $ormFactory;
    private $fetchUser;

    public function __construct(
        OrmFactory $ormFactory,
        FetchUserService $fetchUser
    ) {
        $this->ormFactory = $ormFactory;
        $this->fetchUser = $fetchUser;
    }

    public function __invoke(string $token): ?UserModel
    {
        return $this->get($token);
    }

    public function get(string $token): ?UserModel
    {
        $record = $this->fetchRecord($token);

        if (! $record) {
            return null;
        }

        $fetchUser = $this->fetchUser;

        return $fetchUser($record->user_guid);
    }

    private function fetchRecord(string $token): ?UserPasswordResetTokenRecord
    {
        return $this->ormFactory->makeOrm()
            ->select(UserPasswordResetToken::class)
            ->orWhere('guid =', $token)
            ->fetchRecord();
    }
}
