<?php

declare(strict_types=1);

namespace corbomite\user\interfaces;

use corbomite\db\interfaces\UuidModelInterface;
use DateTimeInterface;

interface UserModelInterface
{
    /**
     * Sets incoming properties from the incoming array
     *
     * @param mixed[] $props
     */
    public function __construct(array $props = []);

    /**
     * Returns value. Sets value if incoming argument set.
     */
    public function guid(?string $val = null) : string;

    /**
     * Gets the UuidModel for the guid
     */
    public function guidAsModel() : UuidModelInterface;

    /**
     * Gets the GUID as bytes for saving to the database in binary
     */
    public function getGuidAsBytes() : string;

    /**
     * Sets the GUID from bytes coming from the database binary column
     *
     * @return mixed
     */
    public function setGuidAsBytes(string $bytes);

    /**
     * Returns value. Sets value if incoming argument set.
     * incoming string value
     */
    public function emailAddress(?string $val = null) : string;

    /**
     * Returns value. Sets value if incoming argument set.
     * incoming string value
     */
    public function passwordHash(?string $val = null) : string;

    /**
     * Returns value. Sets value if incoming argument set.
     *
     * @param mixed[]|null $val
     *
     * @return mixed[]
     */
    public function userData(?array $val = null) : array;

    /**
     * Returns the value from the specified key in the data array if that key
     * exists, sets it if there is a specified incoming value
     *
     * @param mixed|null $val
     *
     * @return mixed
     */
    public function userDataItem(string $key, $val = null);

    /**
     * Returns value. Sets value if incoming argument set.
     */
    public function addedAt(?DateTimeInterface $val = null) : ?DateTimeInterface;

    /**
     * Returns value. Sets value if incoming argument set.
     *
     * @param mixed[] $val
     *
     * @return mixed[]
     */
    public function extendedProperties(?array $val = null) : array;

    /**
     * Sets the value of an extended property
     *
     * @param mixed $val
     */
    public function setExtendedProperty(string $key, $val) : void;

    /**
     * Checks if an extended property exists.
     * Using getExtendedProperty and checking for null would be unreliable
     * since the value of an extended property could be null.
     */
    public function hasExtendedProperty(string $key) : bool;

    /**
     * Gets the value of an extended property
     *
     * @return mixed|null
     */
    public function getExtendedProperty(string $key);

    /**
     * Removes an extended property
     *
     * @return mixed
     */
    public function removeExtendedProperty(string $key);
}
