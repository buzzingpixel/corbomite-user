<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use Ramsey\Uuid\UuidFactoryInterface;
use corbomite\db\Factory as OrmFactory;
use corbomite\user\interfaces\UserModelInterface;
use corbomite\user\data\UserPasswordResetToken\UserPasswordResetToken;
use corbomite\user\data\UserPasswordResetToken\UserPasswordResetTokenRecord;

class GetUserByPasswordResetTokenService
{
    private $fetchUser;
    private $ormFactory;
    private $uuidFactory;

    public function __construct(
        OrmFactory $ormFactory,
        FetchUserService $fetchUser,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->fetchUser = $fetchUser;
        $this->ormFactory = $ormFactory;
        $this->uuidFactory = $uuidFactory;
    }

    public function __invoke(string $token): ?UserModelInterface
    {
        return $this->get($token);
    }

    public function get(string $token): ?UserModelInterface
    {
        $record = $this->fetchRecord($token);

        if (! $record) {
            return null;
        }

        return $this->fetchUser->fetchUser($record->user_guid);
    }

    private function fetchRecord(string $token): ?UserPasswordResetTokenRecord
    {
        if (! $this->isBinary($token)) {
            $token = $this->uuidFactory->fromString($token)->getBytes();
        }

        return $this->ormFactory->makeOrm()
            ->select(UserPasswordResetToken::class)
            ->orWhere('guid =', $token)
            ->fetchRecord();
    }

    private function isBinary($str): bool
    {
        return preg_match('~[^\x20-\x7E\t\r\n]~', $str) > 0;
    }
}
