<?php

/**
 * @description        : 选择语言
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-07-25 14:41:25
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Session::has('locale') && in_array(Session::get('locale'), ['en', 'zh'])) {
            App::setLocale(Session::get('locale'));
        } else {
            App::setLocale('zh');
        }
        return $next($request);
    }
}
