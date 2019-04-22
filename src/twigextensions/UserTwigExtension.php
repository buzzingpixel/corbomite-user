<?php

declare(strict_types=1);

namespace corbomite\user\twigextensions;

use corbomite\user\interfaces\UserApiInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserTwigExtension extends AbstractExtension
{
    /** @var UserApiInterface */
    private $userApi;

    public function __construct(UserApiInterface $userApi)
    {
        $this->userApi = $userApi;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions() : array
    {
        return [new TwigFunction('userApi', [$this, 'userApi'])];
    }

    public function userApi() : UserApiInterface
    {
        return $this->userApi;
    }
}
