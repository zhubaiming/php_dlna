<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        $this->checkForToken($request);

//        if (empty($guards)) {
//            $guards = [null];
//        }
//
//        foreach ($guards as $guard) {
//            if ($this->auth->guard($guard)->check()) {
//                return $this->auth->shouldUse($guard);
//            }
//        }
//
//        $this->unauthenticated($request, $guards);

        parent::authenticate($request, $guards);

//        try {
//
//        } catch (TokenExpiredException $tokenExpiredException) { // token 过期异常，可在此处刷新 token
//
//        }
    }

    protected function checkForToken($request): void
    {
        if (!app()->make('tymon.jwt')->parser()->setRequest($request)->hasToken()) {
            throw new UnauthorizedHttpException('auth');
        }
    }
}
