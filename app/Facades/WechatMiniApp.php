<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WechatMiniApp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'wechat_mini_app';
    }
}
