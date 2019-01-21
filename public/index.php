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
// $queryModel->addWhere('email_address', 'asdf');
// var_dump($userApi->fetchOne($queryModel));
// die;

// var_dump($userApi->logUserIn('c760d1db-d905-4914-930b-c07da0a5f1b0', '1234qwertY'));
// die;

// $userApi->registerUser('test@buzzingpixel.com', 'qwertyuiop1234567890');

// $user = $userApi->fetchUser('test@buzzingpixel.com');
// $user->setExtendedProperty('test', 'things');
// $userApi->saveUser($user);
// var_dump($user);
// die;

$user = $userApi->fetchCurrentUser();
$user->setExtendedProperty('test', 'testing 123');
$userApi->saveUser($user);
