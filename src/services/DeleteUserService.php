<?php

declare(strict_types=1);

namespace corbomite\user\services;

use corbomite\events\interfaces\EventDispatcherInterface;
use corbomite\user\events\UserAfterDeleteEvent;
use corbomite\user\events\UserBeforeDeleteEvent;
use corbomite\user\interfaces\UserModelInterface;
use PDO;

class DeleteUserService
{
    /** @var PDO */
    private $pdo;
    /** @var EventDispatcherInterface */
    private $dispatcher;

    public function __construct(
        PDO $pdo,
        EventDispatcherInterface $dispatcher
    ) {
        $this->pdo        = $pdo;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(UserModelInterface $userModel) : void
    {
        $this->delete($userModel);
    }

    public function delete(UserModelInterface $user) : void
    {
        $this->dispatcher->dispatch(new UserBeforeDeleteEvent($user));

        $statement = $this->pdo->prepare('DELETE FROM `users` WHERE guid=:guid');
        $statement->execute([
            ':guid' => $user->getGuidAsBytes(),
        ]);

        $statement = $this->pdo->prepare('DELETE FROM `user_sessions` WHERE user_guid=:user_guid');
        $statement->execute([
            ':user_guid' => $user->getGuidAsBytes(),
        ]);

        $statement = $this->pdo->prepare('DELETE FROM `user_password_reset_tokens` WHERE user_guid=:user_guid');
        $statement->execute([
            ':user_guid' => $user->getGuidAsBytes(),
        ]);

        $this->dispatcher->dispatch(new UserAfterDeleteEvent($user));
    }
}
