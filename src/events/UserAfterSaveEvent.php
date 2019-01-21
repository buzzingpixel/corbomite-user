<?php
declare(strict_types=1);

namespace corbomite\user\events;

use corbomite\user\UserApi;
use corbomite\events\interfaces\EventInterface;
use corbomite\user\interfaces\UserModelInterface;

class UserAfterSaveEvent implements EventInterface
{
    private $wasNew;
    private $userModel;

    public function __construct(
        UserModelInterface $userModel,
        bool $wasNew
    ) {
        $this->wasNew = $wasNew;
        $this->userModel = $userModel;
    }

    public function wasNew(): bool
    {
        return $this->wasNew;
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
        return 'UserAfterSave';
    }

    private $stop = false;

    public function stopPropagation(?bool $stop = null): bool
    {
        return $this->stop = $stop ?? $this->stop;
    }
}
