<?php

declare(strict_types=1);

namespace corbomite\user\services;

use buzzingpixel\cookieapi\CookieApi;
use corbomite\db\Factory as OrmFactory;
use corbomite\user\data\UserSession\UserSession;
use Ramsey\Uuid\UuidFactoryInterface;

class LogCurrentUserOutService
{
    /** @var OrmFactory */
    private $ormFactory;
    /** @var CookieApi */
    private $cookieApi;
    /** @var UuidFactoryInterface */
    private $uuidFactory;

    public function __construct(
        OrmFactory $ormFactory,
        CookieApi $cookieApi,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->ormFactory  = $ormFactory;
        $this->cookieApi   = $cookieApi;
        $this->uuidFactory = $uuidFactory;
    }

    public function __invoke() : void
    {
        $this->logCurrentUserOut();
    }

    public function logCurrentUserOut() : void
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
