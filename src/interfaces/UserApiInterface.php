<?php

declare(strict_types=1);

namespace corbomite\user\interfaces;

use corbomite\db\interfaces\QueryModelInterface;

interface UserApiInterface
{
    /**
     * Registers a user
     *
     * @return mixed
     */
    public function registerUser(string $emailAddress, string $password);

    /**
     * Saves a user
     *
     * @return mixed
     */
    public function saveUser(UserModelInterface $user);

    /**
     * Fetches a user based on guid or email address
     */
    public function fetchUser(string $identifier) : ?UserModelInterface;

    /**
     * Creates a Fetch User Params Model
     */
    public function makeQueryModel() : QueryModelInterface;

    /**
     * Fetches the currently logged in user
     */
    public function fetchCurrentUser() : ?UserModelInterface;

    /**
     * Fetches one matching user based on params model settings
     */
    public function fetchOne(?QueryModelInterface $queryModel = null) : ?UserModelInterface;

    /**
     * Fetches all matching users based on params model settings
     *
     * @return UserModelInterface[]
     */
    public function fetchAll(?QueryModelInterface $queryModel = null) : array;

    /**
     * Validates a password against the specified user identifier
     */
    public function validateUserPassword(
        string $identifier,
        string $password
    ) : bool;

    /**
     * Logs user in by email address
     *
     * @return mixed
     */
    public function logUserIn(string $emailAddress, string $password);

    /**
     * Logs out the currently logged in user
     *
     * @return mixed
     */
    public function logCurrentUserOut();

    /**
     * Generates a password reset token for the specified user
     */
    public function generatePasswordResetToken(UserModelInterface $user) : string;

    /**
     * Gets user by password reset token
     */
    public function getUserByPasswordResetToken(string $token) : ?UserModelInterface;

    /**
     * Resets a password by token
     *
     * @return mixed
     */
    public function resetPasswordByToken(string $token, string $password);

    /**
     * Sets a new password on specified user
     *
     * @return mixed
     */
    public function setNewPassword(UserModelInterface $user, string $password);

    /**
     * Deletes a user
     *
     * @return mixed
     */
    public function deleteUser(UserModelInterface $user);
}
