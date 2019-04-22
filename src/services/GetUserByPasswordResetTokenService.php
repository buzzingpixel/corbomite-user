<?php

declare(strict_types=1);

namespace corbomite\user\services;

use corbomite\db\Factory as OrmFactory;
use corbomite\user\data\UserPasswordResetToken\UserPasswordResetToken;
use corbomite\user\data\UserPasswordResetToken\UserPasswordResetTokenRecord;
use corbomite\user\interfaces\UserModelInterface;
use Ramsey\Uuid\UuidFactoryInterface;
use function preg_match;

class GetUserByPasswordResetTokenService
{
    /** @var OrmFactory */
    private $ormFactory;
    /** @var FetchUserService */
    private $fetchUser;
    /** @var UuidFactoryInterface */
    private $uuidFactory;

    public function __construct(
        OrmFactory $ormFactory,
        FetchUserService $fetchUser,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->ormFactory  = $ormFactory;
        $this->fetchUser   = $fetchUser;
        $this->uuidFactory = $uuidFactory;
    }

    public function __invoke(string $token) : ?UserModelInterface
    {
        return $this->get($token);
    }

    public function get(string $token) : ?UserModelInterface
    {
        $record = $this->fetchRecord($token);

        if (! $record) {
            return null;
        }

        return $this->fetchUser->fetchUser($record->user_guid);
    }

    private function fetchRecord(string $token) : ?UserPasswordResetTokenRecord
    {
        if (! $this->isBinary($token)) {
            $token = $this->uuidFactory->fromString($token)->getBytes();
        }

        return $this->ormFactory->makeOrm()
            ->select(UserPasswordResetToken::class)
            ->orWhere('guid =', $token)
            ->fetchRecord();
    }

    /**
     * @param mixed $str
     */
    private function isBinary($str) : bool
    {
        return preg_match('~[^\x20-\x7E\t\r\n]~', $str) > 0;
    }
}
