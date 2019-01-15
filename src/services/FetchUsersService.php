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
use corbomite\user\data\User\UserSelect;
use corbomite\user\interfaces\UserModelInterface;
use corbomite\user\interfaces\FetchUsersParamsModelInterface;
use corbomite\user\interfaces\UserRecordToModelTransformerInterface;

class FetchUsersService
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

    /**
     * @return UserModelInterface[]
     */
    public function __invoke(FetchUsersParamsModelInterface $paramsModel): array
    {
        return $this->fetch($paramsModel);
    }

    /**
     * @return UserModelInterface[]
     */
    public function fetch(FetchUsersParamsModelInterface $paramsModel): array
    {
        $models = [];

        foreach ($this->buildQuery($paramsModel)->fetchRecords() as $record) {
            $models[] = $this->userRecordToModel->transform($record);
        }

        return $models;
    }

    private function buildQuery(FetchUsersParamsModelInterface $paramsModel): UserSelect
    {
        $query = $this->ormFactory->makeOrm()->select(User::class)
            ->offset($paramsModel->offset())
            ->orderBy($paramsModel->orderBy() . ' ' . $paramsModel->sort());

        if ($paramsModel->limit()) {
            $query->limit($paramsModel->limit());
        }

        return $query;
    }
}
