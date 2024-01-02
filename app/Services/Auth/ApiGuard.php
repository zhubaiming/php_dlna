<?php

namespace App\Services\Auth;

use Illuminate\Contracts\Auth\UserProvider;

class ApiGuard
{
    public static function setClass(string $name, UserProvider $userProvider)
    {
        $className = 'App\Services\Auth\\' . ucfirst($name) . 'Auth';

        $guard = new $className($name, $userProvider);

        if (method_exists($guard, 'setDispatcher')) {
            $guard->setDispatcher(app('events'));
        }

        if (method_exists($guard, 'setRequest')) {
            $guard->setRequest(app()->refresh('request', $guard, 'setRequest'));
        }

        return $guard;
    }
}
