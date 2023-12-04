<?php

namespace App\Services\Weixin;

class Base
{
    protected $appId;

    protected $appSecret;

    public function __construct()
    {
        $this->appId = 'wx3c1e42206a0ecabd';

        $this->appSecret = '8c747dbce604aed92c9806863db04f7c';
    }
}
