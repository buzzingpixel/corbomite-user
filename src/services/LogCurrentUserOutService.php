<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\services;

use Ramsey\Uuid\UuidFactoryInterface;
use buzzingpixel\cookieapi\CookieApi;
use corbomite\db\Factory as OrmFactory;
use corbomite\user\data\UserSession\UserSession;

class LogCurrentUserOutService
{
    private $cookieApi;
    private $ormFactory;
    private $uuidFactory;

    public function __construct(
        OrmFactory $ormFactory,
        CookieApi $cookieApi,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->cookieApi = $cookieApi;
        $this->ormFactory = $ormFactory;
        $this->uuidFactory = $uuidFactory;
    }

    public function __invoke(): void
    {
        $this->logCurrentUserOut();
    }

    public function logCurrentUserOut(): void
    {
        $cookie = $this->cookieApi->retrieveCookie('user_session_token');

        if (! $cookie) {
            return;
        }

        $orm = $this->ormFactory->makeOrm();

        $record = $orm->select(UserSession::class)
            ->where(
                'guid = ',
                $this->uuidFactory->fromString($cookie->value())->getBytes()
            )
            ->fetchRecord();

        if ($record) {
            $orm->delete($record);
        }

        $this->cookieApi->deleteCookie($cookie);
    }
}
