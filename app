#!/usr/bin/env php
<?php

declare(strict_types=1);

use corbomite\di\Di;
use corbomite\cli\Kernel;

putenv('DB_HOST=db');
putenv('DB_DATABASE=site');
putenv('DB_USER=site');
putenv('DB_PASSWORD=secret');
putenv('CORBOMITE_DB_DATA_NAMESPACE=corbomite\user\data');
putenv('CORBOMITE_DB_DATA_DIRECTORY=./src/data');

require __DIR__ . '/vendor/autoload.php';

/** @noinspection PhpUnhandledExceptionInspection */
Di::diContainer()->get(Kernel::class)($argv);
