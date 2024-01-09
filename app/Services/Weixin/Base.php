<?php

namespace App\Services\Weixin;

use App\Services\HttpTrait;

class Base
{
    use HttpTrait;

    protected $appId;

    protected $appSecret;

    public function __construct()
    {
        $this->appId = config('services.wechat.mini_app.app_id');

        $this->appSecret = config('services.wechat.mini_app.app_secret');
    }
}
