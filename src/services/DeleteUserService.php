<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use PDO;
use LogicException;
use corbomite\user\interfaces\UserModelInterface;
use src\app\projects\events\UserAfterDeleteEvent;
use src\app\projects\events\UserBeforeDeleteEvent;
use corbomite\events\interfaces\EventDispatcherInterface;

class DeleteUserService
{
    private $pdo;
    private $dispatcher;

    public function __construct(
        PDO $pdo,
        EventDispatcherInterface $dispatcher
    ) {
        $this->pdo = $pdo;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(UserModelInterface $userModel): void
    {
        $this->delete($userModel);
    }

    public function delete(UserModelInterface $user): void
    {
        if (! $user->guid()) {
            throw new LogicException('User Model GUID is not set');
        }

        $before = new UserBeforeDeleteEvent($user);

        $this->dispatcher->dispatch($before->name(), $before->provider(), $before);

        $statement = $this->pdo->prepare('DELETE FROM `users` WHERE guid=:guid');
        $statement->execute([
            ':guid' => $user->guid(),
        ]);

        $statement = $this->pdo->prepare('DELETE FROM `user_sessions` WHERE user_guid=:user_guid');
        $statement->execute([
            ':user_guid' => $user->guid(),
        ]);

        $statement = $this->pdo->prepare('DELETE FROM `user_password_reset_tokens` WHERE user_guid=:user_guid');
        $statement->execute([
            ':user_guid' => $user->guid(),
        ]);

        $after = new UserAfterDeleteEvent($user);

        $this->dispatcher->dispatch($after->name(), $after->provider(), $after);
    }
}
