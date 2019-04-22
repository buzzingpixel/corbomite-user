<?php

declare(strict_types=1);

namespace corbomite\user\services;

use corbomite\db\Factory as DbFactory;
use corbomite\user\interfaces\UserModelInterface;
use Ramsey\Uuid\UuidFactoryInterface;
use const FILTER_VALIDATE_EMAIL;
use function filter_var;
use function preg_match;

class FetchUserService
{
    /** @var DbFactory */
    private $dbFactory;
    /** @var FetchUsersService */
    private $fetchUsers;
    /** @var UuidFactoryInterface */
    private $uuidFactory;

    public function __construct(
        DbFactory $dbFactory,
        FetchUsersService $fetchUsers,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->dbFactory   = $dbFactory;
        $this->fetchUsers  = $fetchUsers;
        $this->uuidFactory = $uuidFactory;
    }

    public function __invoke(string $identifier) : ?UserModelInterface
    {
        return $this->fetchUser($identifier);
    }

    public function fetchUser(string $identifier) : ?UserModelInterface
    {
        $queryModel = $this->dbFactory->makeQueryModel();
        $queryModel->limit(1);

        $isBinary = $this->isBinary($identifier);

        if ($isBinary) {
            $queryModel->addWhere('guid', $identifier);
        }

        if (! $isBinary) {
            $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);

            if ($isEmail) {
                $queryModel->addWhere('email_address', $identifier);
            }

            if (! $isEmail) {
                $queryModel->addWhere(
                    'guid',
                    $this->uuidFactory->fromString($identifier)->getBytes()
                );
            }
        }

        return $this->fetchUsers->fetch($queryModel)[0] ?? null;
    }

    /**
     * @param mixed $str
     */
    private function isBinary($str) : bool
    {
        return preg_match('~[^\x20-\x7E\t\r\n]~', $str) > 0;
    }
}
