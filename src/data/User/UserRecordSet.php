<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\data\User;

use Atlas\Mapper\RecordSet;

/**
 * @method UserRecord offsetGet($offset)
 * @method UserRecord appendNew(array $fields = [])
 * @method UserRecord|null getOneBy(array $whereEquals)
 * @method UserRecordSet getAllBy(array $whereEquals)
 * @method UserRecord|null detachOneBy(array $whereEquals)
 * @method UserRecordSet detachAllBy(array $whereEquals)
 * @method UserRecordSet detachAll()
 * @method UserRecordSet detachDeleted()
 */
class UserRecordSet extends RecordSet
{
}
