<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use corbomite\db\Factory as DbFactory;
use corbomite\user\interfaces\UserModelInterface;

class FetchUserService
{
    private $dbFactory;
    private $fetchUsers;

    public function __construct(
        DbFactory $dbFactory,
        FetchUsersService $fetchUsers
    ) {
        $this->dbFactory = $dbFactory;
        $this->fetchUsers = $fetchUsers;
    }

    public function __invoke(string $identifier): ?UserModelInterface
    {
        return $this->fetchUser($identifier);
    }

    public function fetchUser(string $identifier): ?UserModelInterface
    {
        $queryModel = $this->dbFactory->makeQueryModel();

        $queryModel->limit(1);

        $queryModel->addWhere('guid', $identifier);

        $queryModel->addWhere('email_address', $identifier, '=', true);

        return $this->fetchUsers->fetch($queryModel)[0] ?? null;
    }
}
