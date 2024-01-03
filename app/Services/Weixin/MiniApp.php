<?php

namespace App\Services\Weixin;

use App\Exceptions\WechatAPIException;
use Illuminate\Http\Client\RequestException;

class MiniApp extends Base
{
    protected function jscodeToSession($code)
    {
        try {
            $this->getHttp('https://api.weixin.qq.com/sns/jscode2session', [
                'appid' => $this->appId,
                'secret' => $this->appSecret,
                'js_code' => $code,
                'grant_type' => 'authorization_code'
            ]);

            if (isset($this->response_body['errcode'])) {
                $str = match ($this->response_body['errcode']) {
                    40163 => 'code已经被使用',
                    40029 => '无效的code',
                    40066 => '无效的请求地址',
                };

                throw new WechatAPIException([
                    'method_name' => __METHOD__,
                    'args' => func_get_arg(0)
                ], $str, $this->response_body['errcode']);
            }

            return json_decode($this->response_body->body(), true);
        } catch (RequestException $e) {
            throw new WechatAPIException([
                'method_name' => __METHOD__,
                'args' => func_get_arg(0)
            ], $e->getMessage(), $e->getCode());
        }
    }
}
