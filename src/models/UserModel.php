<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\models;

use DateTime;
use corbomite\user\interfaces\UserModelInterface;

class UserModel implements UserModelInterface
{
    public function __construct(array $props = [])
    {
        foreach ($props as $key => $val) {
            $this->{$key}($val);
        }
    }

    private $guid = '';

    public function guid(?string $guid = null): string
    {
        return $this->guid = $guid !== null ? $guid : $this->guid;
    }

    private $emailAddress = '';

    public function emailAddress(?string $emailAddress = null): string
    {
        return $this->emailAddress = $emailAddress !== null ?
            $emailAddress :
            $this->emailAddress;
    }

    private $passwordHash = '';

    public function passwordHash(?string $passwordHash = null): string
    {
        return $this->passwordHash = $passwordHash !== null ?
            $passwordHash :
            $this->passwordHash;
    }

    private $userData = [];

    public function userData(?array $userData = null): array
    {
        return $this->userData = $userData !== null ? $userData : $this->userData;
    }

    public function userDataItem(string $key, $val = null)
    {
        if ($val !== null) {
            $this->setUserDataItem($key, $val);
        }

        return $this->getUserDataItem($key);
    }

    private function setUserDataItem(string $key, $val)
    {
        $loc = &$this->userData;

        foreach (explode('.', $key) as $step) {
            $loc = &$loc[$step];
        }

        $loc = $val;
    }

    private function getUserDataItem(string $key)
    {
        $val = $this->userData;

        foreach (explode('.', $key) as $step) {
            if (! isset($val[$step])) {
                return null;
            }

            $val = $val[$step];
        }

        return $val;
    }

    private $addedAt;

    public function addedAt(?DateTime $addedAt = null): ?DateTime
    {
        return $this->addedAt = $addedAt !== null ? $addedAt : $this->addedAt;
    }
}
