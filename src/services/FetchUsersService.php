<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use PDO;
use corbomite\user\data\User\User;
use corbomite\db\interfaces\BuildQueryInterface;
use corbomite\db\interfaces\QueryModelInterface;
use corbomite\user\interfaces\UserModelInterface;
use corbomite\user\interfaces\UserRecordToModelTransformerInterface;

class FetchUsersService
{
    private $pdo;
    private $buildQuery;
    private $userRecordToModel;

    public function __construct(
        PDO $pdo,
        BuildQueryInterface $buildQuery,
        UserRecordToModelTransformerInterface $userRecordToModel
    ) {
        $this->pdo = $pdo;
        $this->buildQuery = $buildQuery;
        $this->userRecordToModel = $userRecordToModel;
    }

    /**
     * @return UserModelInterface[]
     */
    public function __invoke(QueryModelInterface $queryModel): array
    {
        return $this->fetch($queryModel);
    }

    /**
     * @return UserModelInterface[]
     */
    public function fetch(QueryModelInterface $queryModel): array
    {
        $query = $this->buildQuery->build(User::class, $queryModel);
        $query->columns('*');

        $bind = [];

        foreach ($query->getBindValues() as $k => $v) {
            if (! isset($v[0])) {
                continue;
            }

            $bind[':' . $k] = $v[0];
        }

        $q = $this->pdo->prepare($query->getStatement());
        $q->execute($bind);

        $result = $q->fetchAll(PDO::FETCH_ASSOC);

        $models = [];

        foreach ($result as $record) {
            $models[] = $this->userRecordToModel->transform($record);
        }

        return $models;
    }
}
