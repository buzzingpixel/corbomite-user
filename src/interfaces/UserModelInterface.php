<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\interfaces;

use DateTime;

interface UserModelInterface
{
    /**
     * Sets incoming properties from the incoming array
     * @param array $props
     */
    public function __construct(array $props = []);

    /**
     * Returns value. Sets value if incoming argument set.
     * @param string|null $guid
     * @return string
     */
    public function guid(?string $val = null): string;

    /**
     * Returns value. Sets value if incoming argument set.
     * incoming string value
     * @param string|null $guid
     * @return string
     */
    public function emailAddress(?string $val = null): string;

    /**
     * Returns value. Sets value if incoming argument set.
     * incoming string value
     * @param string|null $guid
     * @return string
     */
    public function passwordHash(?string $val = null): string;

    /**
     * Returns value. Sets value if incoming argument set.
     * @param array|null $guid
     * @return string
     */
    public function userData(?array $val = null): array;

    /**
     * Returns the value from the specified key in the data array if that key
     * exists, sets it if there is a specified incoming value
     * @param string $key
     * @param mixed|null $val
     * @return mixed
     */
    public function userDataItem(string $key, $val = null);

    /**
     * Returns value. Sets value if incoming argument set.
     * @param DateTime|null $addedAt
     * @return DateTime|null
     */
    public function addedAt(?DateTime $val = null): ?DateTime;

    /**
     * Returns value. Sets value if incoming argument set.
     * @param array|null $guid
     * @return string
     */
    public function extendedProperties(?array $val = null): array;

    /**
     * Sets the value of an extended property
     * @param string $key
     * @param mixed $val
     */
    public function setExtendedProperty(string $key, $val): void;

    /**
     * Checks if an extended property exists.
     * Using getExtendedProperty and checking for null would be unreliable
     * since the value of an extended property could be null.
     * @param string $key
     * @return bool
     */
    public function hasExtendedProperty(string $key): bool;

    /**
     * Gets the value of an extended property
     * @param string $key
     * @return mixed|null
     */
    public function getExtendedProperty(string $key);

    /**
     * Removes an extended property
     * @param string $key
     */
    public function removeExtendedProperty(string $key);
}
