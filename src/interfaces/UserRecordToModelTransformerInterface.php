<?php

declare(strict_types=1);

namespace corbomite\user\interfaces;

interface UserRecordToModelTransformerInterface
{
    /**
     * Transforms a user record into a user model
     *
     * @param mixed[] $record Array of record values from PDO fetch
     */
    public function __invoke(array $record) : UserModelInterface;

    /**
     * Transforms a user record into a user model
     *
     * @param mixed[] $record Array of record values from PDO fetch
     */
    public function transform(array $record) : UserModelInterface;
}
