<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\data\UserPasswordResetToken;

use Atlas\Mapper\Record;

/**
 * @method UserPasswordResetTokenRow getRow()
 */
class UserPasswordResetTokenRecord extends Record
{
    use UserPasswordResetTokenFields;
}
