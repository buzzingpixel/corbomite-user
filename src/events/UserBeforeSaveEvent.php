<?php

declare(strict_types=1);

namespace corbomite\user\events;

use corbomite\events\interfaces\EventInterface;
use corbomite\user\interfaces\UserModelInterface;
use corbomite\user\UserApi;

class UserBeforeSaveEvent implements EventInterface
{
    /** @var bool */
    private $isNew;
    /** @var UserModelInterface */
    private $userModel;

    public function __construct(
        UserModelInterface $userModel,
        bool $isNew
    ) {
        $this->isNew     = $isNew;
        $this->userModel = $userModel;
    }

    public function isNew() : bool
    {
        return $this->isNew;
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
        return 'UserBeforeSave';
    }

    /** @var bool */
    private $stop = false;

    public function stopPropagation(?bool $stop = null) : bool
    {
        return $this->stop = $stop ?? $this->stop;
    }
}
