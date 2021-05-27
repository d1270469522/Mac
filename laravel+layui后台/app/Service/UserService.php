<?php

/**
 * @description        : 天尽头流浪
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-07-18 15:22:59
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Service;

use Illuminate\Support\Facades\Facade;

/**
 * @method static attemptLogin($paramArr = [])
 * @method static encrypt($pwd)
 */
class UserService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'App\Service\Foundation\UserService';
    }
}
