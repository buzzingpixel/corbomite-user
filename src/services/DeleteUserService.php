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

class DeleteUserService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function __invoke(UserModelInterface $userModel): void
    {
        $this->delete($userModel);
    }

    public function delete(UserModelInterface $userModel): void
    {
        if (! $userModel->guid()) {
            throw new LogicException('User Model GUID is not set');
        }

        $statement = $this->pdo->prepare('DELETE FROM `users` WHERE guid=:guid');
        $statement->execute([
            ':guid' => $userModel->guid(),
        ]);

        $statement = $this->pdo->prepare('DELETE FROM `user_sessions` WHERE user_guid=:user_guid');
        $statement->execute([
            ':user_guid' => $userModel->guid(),
        ]);

        $statement = $this->pdo->prepare('DELETE FROM `user_password_reset_tokens` WHERE user_guid=:user_guid');
        $statement->execute([
            ':user_guid' => $userModel->guid(),
        ]);
    }
}
