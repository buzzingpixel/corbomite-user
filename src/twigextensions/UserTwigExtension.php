<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2018 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\twigextensions;

use Twig_Function;
use Twig_Extension;
use corbomite\user\interfaces\UserApiInterface;

class UserTwigExtension extends Twig_Extension
{
    private $userApi;

    public function __construct(UserApiInterface $userApi)
    {
        $this->userApi = $userApi;
    }

    public function getFunctions(): array
    {
        return [new Twig_Function('userApi', [$this, 'userApi'])];
    }

    public function userApi(): UserApiInterface
    {
        return $this->userApi;
    }
}
