<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\data\UserPasswordResetToken;

use Atlas\Mapper\MapperSelect;

/**
 * @method UserPasswordResetTokenRecord|null fetchRecord()
 * @method UserPasswordResetTokenRecord[] fetchRecords()
 * @method UserPasswordResetTokenRecordSet fetchRecordSet()
 */
class UserPasswordResetTokenSelect extends MapperSelect
{
}
