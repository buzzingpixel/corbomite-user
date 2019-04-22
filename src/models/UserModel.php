<?php

declare(strict_types=1);

namespace corbomite\user\models;

use corbomite\db\traits\UuidTrait;
use corbomite\user\interfaces\UserModelInterface;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use function array_key_exists;
use function explode;

class UserModel implements UserModelInterface
{
    use UuidTrait;

    /** @noinspection PhpDocMissingThrowsInspection */

    /**
     * @param mixed[] $props
     */
    public function __construct(array $props = [])
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->addedAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));

        foreach ($props as $key => $val) {
            $this->{$key}($val);
        }
    }

    /** @var string */
    private $emailAddress = '';

    public function emailAddress(?string $emailAddress = null) : string
    {
        return $this->emailAddress = $emailAddress ?? $this->emailAddress;
    }

    /** @var string */
    private $passwordHash = '';

    public function passwordHash(?string $passwordHash = null) : string
    {
        return $this->passwordHash = $passwordHash ?? $this->passwordHash;
    }

    /** @var mixed[] */
    private $userData = [];

    /**
     * @param mixed[]|null $userData
     *
     * @return mixed[]
     */
    public function userData(?array $userData = null) : array
    {
        return $this->userData = $userData ?? $this->userData;
    }

    /**
     * @param mixed $val
     *
     * @return mixed
     */
    public function userDataItem(string $key, $val = null)
    {
        if ($val !== null) {
            $this->setUserDataItem($key, $val);
        }

        return $this->getUserDataItem($key);
    }

    /**
     * @param mixed $val
     */
    private function setUserDataItem(string $key, $val) : void
    {
        $loc = &$this->userData;

        foreach (explode('.', $key) as $step) {
            $loc = &$loc[$step];
        }

        $loc = $val;
    }

    /**
     * @return mixed
     */
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

    /** @var DateTimeInterface */
    private $addedAt;

    public function addedAt(?DateTimeInterface $addedAt = null) : ?DateTimeInterface
    {
        if (! $addedAt) {
            return $this->addedAt;
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->addedAt = (new DateTimeImmutable())
            ->setTimestamp($addedAt->getTimestamp())
            ->setTimezone($addedAt->getTimezone());

        return $this->addedAt;
    }

    /** @var mixed[] */
    private $extendedProperties = [];

    /**
     * @param mixed[] $val
     *
     * @return mixed[]
     */
    public function extendedProperties(?array $val = null) : array
    {
        return $this->extendedProperties = $val ?? $this->extendedProperties;
    }

    /**
     * @param mixed $val
     */
    public function setExtendedProperty(string $key, $val) : void
    {
        $this->extendedProperties[$key] = $val;
    }

    public function hasExtendedProperty(string $key) : bool
    {
        return array_key_exists($key, $this->extendedProperties);
    }

    /**
     * @return mixed
     */
    public function getExtendedProperty(string $key)
    {
        return $this->extendedProperties[$key] ?? null;
    }

    public function removeExtendedProperty(string $key) : void
    {
        if (! $this->hasExtendedProperty($key)) {
            return;
        }

        unset($this->extendedProperties[$key]);
    }
}
