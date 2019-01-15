<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\interfaces;

interface FetchUsersParamsModelInterface
{
    /**
     * Sets incoming properties from the incoming array
     * @param array $props
     */
    public function __construct(array $props = []);

    /**
     * Returns the value of limit, sets limit if there is an incoming int value
     * @param int|null $guid
     * @return string
     */
    public function limit(?int $limit = null): int;

    /**
     * Returns the value of offset, sets offset if there is an incoming int value
     * @param int|null $guid
     * @return string
     */
    public function offset(?int $offset = null): int;

    /**
     * Returns the value of orderBy, sets orderBy if there is an incoming string value
     * @param string|null $guid
     * @return string
     */
    public function orderBy(?string $orderBy = null): string;

    /**
     * Returns the value of sort, sets sort if there is an incoming string value
     * @param string|null $guid
     * @return string
     */
    public function sort(?string $sort = null): string;
}
