<?php
declare(strict_types=1);

namespace src\app\projects\events;

use corbomite\user\UserApi;
use corbomite\events\interfaces\EventInterface;
use corbomite\user\interfaces\UserModelInterface;

class UserBeforeLogInEvent implements EventInterface
{
    private $userModel;

    public function __construct(UserModelInterface $userModel)
    {
        $this->userModel = $userModel;
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
        return 'UserBeforeLogin';
    }

    private $stop = false;

    public function stopPropagation(?bool $stop = null): bool
    {
        return $this->stop = $stop ?? $this->stop;
    }
}
