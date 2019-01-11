<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\data\User;

use Atlas\Mapper\Record;

/**
 * @method UserRow getRow()
 */
class UserRecord extends Record
{
    use UserFields;
}
