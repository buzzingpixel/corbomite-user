<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use corbomite\user\data\User\User;
use corbomite\user\models\UserModel;
use corbomite\db\Factory as OrmFactory;
use corbomite\user\data\User\UserSelect;
use corbomite\user\models\FetchUsersParamsModel;
use corbomite\user\transformers\UserRecordToModelTransformer;

class FetchUsersService
{
    private $ormFactory;
    private $userRecordToModel;

    public function __construct(
        OrmFactory $ormFactory,
        UserRecordToModelTransformer $userRecordToModel
    ) {
        $this->ormFactory = $ormFactory;
        $this->userRecordToModel = $userRecordToModel;
    }

    /**
     * @return UserModel[]
     */
    public function __invoke(FetchUsersParamsModel $paramsModel): array
    {
        return $this->fetch($paramsModel);
    }

    /**
     * @return UserModel[]
     */
    public function fetch(FetchUsersParamsModel $paramsModel): array
    {
        $models = [];

        foreach ($this->buildQuery($paramsModel)->fetchRecords() as $record) {
            $models[] = $this->userRecordToModel->transform($record);
        }

        return $models;
    }

    private function buildQuery(FetchUsersParamsModel $paramsModel): UserSelect
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
