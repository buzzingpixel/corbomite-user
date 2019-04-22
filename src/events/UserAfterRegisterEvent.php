<?php

declare(strict_types=1);

namespace corbomite\user\events;

use corbomite\events\interfaces\EventInterface;
use corbomite\user\interfaces\UserModelInterface;
use corbomite\user\UserApi;

class UserAfterRegisterEvent implements EventInterface
{
    /** @var UserModelInterface */
    private $userModel;

    public function __construct(UserModelInterface $userModel)
    {
        $this->userModel = $userModel;
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
        return 'UserAfterRegister';
    }

    /** @var bool */
    private $stop = false;

    public function stopPropagation(?bool $stop = null) : bool
    {
        return $this->stop = $stop ?? $this->stop;
    }
}
