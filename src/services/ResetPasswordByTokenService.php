<?php

declare(strict_types=1);

namespace corbomite\user\services;

use corbomite\db\PDO;
use corbomite\user\exceptions\InvalidEmailAddressException;
use corbomite\user\exceptions\InvalidResetTokenException;
use corbomite\user\exceptions\InvalidUserModelException;
use corbomite\user\exceptions\PasswordTooShortException;
use corbomite\user\exceptions\UserDoesNotExistException;
use corbomite\user\exceptions\UserExistsException;
use Ramsey\Uuid\UuidFactoryInterface;
use const PASSWORD_DEFAULT;
use function mb_strlen;
use function password_hash;
use function preg_match;

class ResetPasswordByTokenService
{
    /** @var PDO */
    private $pdo;
    /** @var SaveUserService */
    private $saveUser;
    /** @var UuidFactoryInterface */
    private $uuidFactory;
    /** @var GetUserByPasswordResetTokenService */
    private $getUserByPasswordResetToken;

    public function __construct(
        PDO $pdo,
        SaveUserService $saveUser,
        UuidFactoryInterface $uuidFactory,
        GetUserByPasswordResetTokenService $getUserByPasswordResetToken
    ) {
        $this->pdo                         = $pdo;
        $this->saveUser                    = $saveUser;
        $this->uuidFactory                 = $uuidFactory;
        $this->getUserByPasswordResetToken = $getUserByPasswordResetToken;
    }

    /**
     * @throws InvalidResetTokenException
     * @throws PasswordTooShortException
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function __invoke(string $token, string $password) : void
    {
        $this->reset($token, $password);
    }

    /**
     * @throws InvalidResetTokenException
     * @throws PasswordTooShortException
     * @throws InvalidEmailAddressException
     * @throws InvalidUserModelException
     * @throws UserDoesNotExistException
     * @throws UserExistsException
     */
    public function reset(string $token, string $password) : void
    {
        if (! $this->isBinary($token)) {
            $token = $this->uuidFactory->fromString($token)->getBytes();
        }

        $model = $this->getUserByPasswordResetToken->get($token);

        if (! $model) {
            throw new InvalidResetTokenException();
        }

        if (mb_strlen($password) < RegisterUserService::MIN_PASSWORD_LENGTH) {
            throw new PasswordTooShortException();
        }

        $model->passwordHash(password_hash($password, PASSWORD_DEFAULT));

        $this->saveUser->saveUser($model);

        $sql = 'DELETE FROM user_password_reset_tokens WHERE guid = ?';
        $q   = $this->pdo->prepare($sql);
        $q->execute([$token]);
    }

    /**
     * @param mixed $str
     */
    private function isBinary($str) : bool
    {
        return preg_match('~[^\x20-\x7E\t\r\n]~', $str) > 0;
    }
}
