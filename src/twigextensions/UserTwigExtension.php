<?php

declare(strict_types=1);

namespace corbomite\user\twigextensions;

use corbomite\user\interfaces\UserApiInterface;
use Twig_Extension;
use Twig_Function;

class UserTwigExtension extends Twig_Extension
{
    /** @var UserApiInterface */
    private $userApi;

    public function __construct(UserApiInterface $userApi)
    {
        $this->userApi = $userApi;
    }

    /**
     * @return Twig_Function[]
     */
    public function getFunctions() : array
    {
        return [new Twig_Function('userApi', [$this, 'userApi'])];
    }

    public function userApi() : UserApiInterface
    {
        return $this->userApi;
    }
}
