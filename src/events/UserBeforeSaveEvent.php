<?php
declare(strict_types=1);

namespace corbomite\user\events;

use corbomite\user\UserApi;
use corbomite\events\interfaces\EventInterface;
use corbomite\user\interfaces\UserModelInterface;

class UserBeforeSaveEvent implements EventInterface
{
    private $isNew;
    private $userModel;

    public function __construct(
        UserModelInterface $userModel,
        bool $isNew
    ) {
        $this->isNew = $isNew;
        $this->userModel = $userModel;
    }

    public function isNew(): bool
    {
        return $this->isNew;
    }

    public function userModel(): UserModelInterface
    {
        return $this->userModel;
    }

    public function provider(): string
    {
        return UserApi::class;
    }

    public function name(): string
    {
        return 'UserBeforeSave';
    }

    private $stop = false;

    public function stopPropagation(?bool $stop = null): bool
    {
        return $this->stop = $stop ?? $this->stop;
    }
}
