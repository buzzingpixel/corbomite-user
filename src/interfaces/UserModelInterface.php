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
     * Returns the value of guid, sets guid if there is an incoming string value
     * @param string|null $guid
     * @return string
     */
    public function guid(?string $guid = null): string;

    /**
     * Returns the value of emailAddress, sets emailAddress if there is an
     * incoming string value
     * @param string|null $guid
     * @return string
     */
    public function emailAddress(?string $emailAddress = null): string;

    /**
     * Returns the value of passwordHash, sets passwordHash if there is an
     * incoming string value
     * @param string|null $guid
     * @return string
     */
    public function passwordHash(?string $passwordHash = null): string;

    /**
     * Returns the value of data, sets data if there is an incoming array value
     * @param array|null $guid
     * @return string
     */
    public function userData(?array $userData = null): array;

    /**
     * Returns the value from the specified key in the data array if that key
     * exists, sets it if there is a specified incoming value
     * @param string $key
     * @param mixed|null $val
     * @return mixed
     */
    public function userDataItem(string $key, $val = null);

    /**
     * Returns the value of addedAt, sets the value if there is an incoming
     * DateTime object
     * @param DateTime|null $addedAt
     * @return DateTime|null
     */
    public function addedAt(?DateTime $addedAt = null): ?DateTime;
}
