<?php

declare(strict_types=1);

namespace corbomite\user\events;

use corbomite\events\interfaces\EventInterface;
use corbomite\user\interfaces\UserModelInterface;
use corbomite\user\UserApi;

class UserAfterSaveEvent implements EventInterface
{
    /** @var bool */
    private $wasNew;
    /** @var UserModelInterface */
    private $userModel;

    public function __construct(
        UserModelInterface $userModel,
        bool $wasNew
    ) {
        $this->wasNew    = $wasNew;
        $this->userModel = $userModel;
    }

    public function wasNew() : bool
    {
        return $this->wasNew;
    }

    public function userModel() : UserModelInterface
    {
        return $this->userModel;
    }

    public function provider() : string
    {
        return UserApi::class;
    }

    public function name() : string
    {
        return 'UserAfterSave';
    }

    /** @var bool */
    private $stop = false;

    public function stopPropagation(?bool $stop = null) : bool
    {
        return $this->stop = $stop ?? $this->stop;
    }
}
