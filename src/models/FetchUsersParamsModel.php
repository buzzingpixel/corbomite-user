<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\models;

class FetchUsersParamsModel
{
    public function __construct(array $props = [])
    {
        foreach ($props as $key => $val) {
            $this->{$key}($val);
        }
    }

    private $limit = 0;

    public function limit(?int $limit = null): int
    {
        return $this->limit = $limit !== null ? $limit : $this->limit;
    }

    private $offset = 0;

    public function offset(?int $offset = null): int
    {
        return $this->offset = $offset !== null ? $offset : $this->offset;
    }

    private $orderBy = 'added_at';

    public function orderBy(?string $orderBy = null): string
    {
        return $this->orderBy = $orderBy !== null ? $orderBy : $this->orderBy;
    }

    private $sort = 'desc';

    public function sort(?string $sort = null): string
    {
        return $this->sort = $sort !== null ?
            strtolower($sort) === 'desc' ? 'DESC' : 'ASC' :
            $this->sort;
    }
}
