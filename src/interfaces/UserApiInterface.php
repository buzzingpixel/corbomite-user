<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\interfaces;

interface UserApiInterface
{
    /**
     * Registers a user
     * @param string $emailAddress
     * @param string $password
     */
    public function registerUser(string $emailAddress, string $password);

    /**
     * Saves a user
     * @param UserModelInterface $user
     */
    public function saveUser(UserModelInterface $user);

    /**
     * Fetches a user based on guid or email address
     * @param string $identifier
     * @return UserModelInterface|null
     */
    public function fetchUser(string $identifier): ?UserModelInterface;

    /**
     * Creates a Fetch User Params Model
     * @param array $props
     * @return FetchUsersParamsModelInterface
     */
    public function createFetchUserParamsModel(array $props = []): FetchUsersParamsModelInterface;

    /**
     * @param FetchUsersParamsModelInterface $paramsModel
     * @return UserModelInterface[]
     */
    public function fetchUsers(FetchUsersParamsModelInterface $paramsModel): array;

    /**
     * Fetches the currently logged in user
     * @return UserModelInterface|null
     */
    public function fetchCurrentUser(): ?UserModelInterface;

    /**
     * Validates a password against the specified user identifier
     * @param string $identifier
     * @param string $password
     * @return bool
     */
    public function validateUserPassword(
        string $identifier,
        string $password
    ): bool;

    /**
     * Logs user in by email address
     * @param string $emailAddress
     * @param string $password
     */
    public function logUserIn(string $emailAddress, string $password);

    /**
     * Logs out the currently logged in user
     */
    public function logCurrentUserOut();

    /**
     * Generates a password reset token for the specified user
     * @param UserModelInterface $user
     * @return string
     */
    public function generatePasswordResetToken(UserModelInterface $user): string;

    /**
     * Gets user by password reset token
     * @param string $token
     * @return UserModelInterface|null
     */
    public function getUserByPasswordResetToken(string $token): ?UserModelInterface;

    /**
     * Resets a password by token
     * @param string $token
     * @param string $password
     */
    public function resetPasswordByToken(string $token, string $password);

    /**
     * Sets a new password on specified user
     * @param UserModelInterface $user
     * @param string $password
     */
    public function setNewPassword(UserModelInterface $user, string $password);
}
