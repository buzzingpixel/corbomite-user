<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

/**
 * This file is for testing purposes only
 */

use corbomite\di\Di;
use corbomite\user\UserApi;

putenv('DEV_MODE=true');

define('APP_BASE_PATH', dirname(__DIR__));


putenv('DB_HOST=db');
putenv('DB_DATABASE=site');
putenv('DB_USER=site');
putenv('DB_PASSWORD=secret');
putenv('CORBOMITE_DB_DATA_NAMESPACE=corbomite\user\data');
putenv('CORBOMITE_DB_DATA_DIRECTORY=./src/data');
putenv('ENCRYPTION_KEY=qwertyuiopasdfghjklzxcvbnm123456');

require_once dirname(__DIR__) . '/vendor/autoload.php';

$userApi = Di::get(UserApi::class);

// var_dump('testing', $userApi);
// die;

// $userApi->registerUser('tj@buzzingpixel.com', '1234qwertY');
// $userApi->registerUser('rachel@buzzingpixel.com', '1234qwertY');
// $userApi->registerUser('test@buzzingpixel.com', '1234qwertY');
// $userApi->registerUser('jkirk@starfleet.galaxy', '1234qwertY');
// die;

// $queryModel = $userApi->makeQueryModel();
// $queryModel->addWhere('email_address', 'tj@buzzingpixel.com');
// var_dump($userApi->fetchOne($queryModel)->guid());
// die;

// var_dump($userApi->logUserIn('8615d150-1ebb-11e9-9f14-0242c0a8f004', '1234qwertY'));
// die;

// var_dump($userApi->fetchCurrentUser());
// die;

// $userApi->registerUser('test@buzzingpixel.com', 'qwertyuiop1234567890');
// die;

// $user = $userApi->fetchUser('test@buzzingpixel.com');
// $user->setExtendedProperty('test', 'things');
// $userApi->saveUser($user);
// var_dump($user);
// die;

// $user = $userApi->fetchCurrentUser();
// $user->setExtendedProperty('test', 'testing 123');
// $userApi->saveUser($user);

// $userApi->registerUser('asdf@buzzingpixel.com', '1234qwertY');
// die;
// $userApi->deleteUser($userApi->fetchUser('asdf@buzzingpixel.com'));
// die;
// $user = $userApi->fetchUser('asdf@buzzingpixel.com');
// var_dump($user);
// die;

// $userApi->logCurrentUserOut();
// die;

// var_dump($userApi->validateUserPassword('jkirk@starfleet.galaxy', '1234qwertY'));
// die;

// var_dump($userApi->generatePasswordResetToken($userApi->fetchUser('jkirk@starfleet.galaxy')));
// die;

// var_dump($userApi->getUserByPasswordResetToken('c044ee0a-64d1-49cf-9bf0-15881ce4b59a'));
// die;

// $userApi->resetPasswordByToken('c044ee0a-64d1-49cf-9bf0-15881ce4b59a', 'poiuytrewQ0987');
// die;

// var_dump($userApi->validateUserPassword('jkirk@starfleet.galaxy', '1234qwertY'));
// var_dump($userApi->validateUserPassword('jkirk@starfleet.galaxy', 'poiuytrewQ0987'));
// die;

// $userApi->setNewPassword($userApi->fetchUser('tj@buzzingpixel.com'), 'lKjHgFdS9*76');
// die;

// var_dump($userApi->validateUserPassword('tj@buzzingpixel.com', '1234qwertY'));
// var_dump($userApi->validateUserPassword('tj@buzzingpixel.com', 'lKjHgFdS9*76'));
// die;
