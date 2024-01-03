<?php

namespace App\Services\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

class WebAuth implements \Illuminate\Contracts\Auth\StatefulGuard
{

    /**
     * @inheritDoc
     */
    public function check()
    {
        // TODO: Implement check() method.
    }

    /**
     * @inheritDoc
     */
    public function guest()
    {
        // TODO: Implement guest() method.
    }

    /**
     * @inheritDoc
     */
    public function user()
    {
        // TODO: Implement user() method.
    }

    /**
     * @inheritDoc
     */
    public function id()
    {
        // TODO: Implement id() method.
    }

    /**
     * @inheritDoc
     */
    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }

    /**
     * @inheritDoc
     */
    public function hasUser()
    {
        // TODO: Implement hasUser() method.
    }

    /**
     * @inheritDoc
     */
    public function setUser(Authenticatable $user)
    {
        // TODO: Implement setUser() method.
    }

    /**
     * @inheritDoc
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        // TODO: Implement attempt() method.
    }

    /**
     * @inheritDoc
     */
    public function once(array $credentials = [])
    {
        // TODO: Implement once() method.
    }

    /**
     * @inheritDoc
     */
    public function login(Authenticatable $user, $remember = false)
    {
        // TODO: Implement login() method.
    }

    /**
     * @inheritDoc
     */
    public function loginUsingId($id, $remember = false)
    {
        // TODO: Implement loginUsingId() method.
    }

    /**
     * @inheritDoc
     */
    public function onceUsingId($id)
    {
        // TODO: Implement onceUsingId() method.
    }

    /**
     * @inheritDoc
     */
    public function viaRemember()
    {
        // TODO: Implement viaRemember() method.
    }

    /**
     * @inheritDoc
     */
    public function logout()
    {
        // TODO: Implement logout() method.
    }
}
