<?php
declare(strict_types=1);

namespace corbomite\user\data\UserSession;

use Atlas\Mapper\Record;

/**
 * @method UserSessionRow getRow()
 */
class UserSessionRecord extends Record
{
    use UserSessionFields;
}
