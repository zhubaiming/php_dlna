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

    public function __call($method, $parameters)
    {
        return $this->{$method}(...$parameters);
    }

    public static function __callStatic(string $func_name, array $args)
    {
        return (new static)->{$func_name}(...$args);
    }
}
