<?php

namespace App\Services\Weixin;

use App\Exceptions\WechatAPIException;
use Illuminate\Http\Client\RequestException;

class MiniApp extends Base
{
    public function jscodeToSession($code)
    {
        try {
            $this->getHttp('https://api.weixin.qq.com/sns/jscode2session', [
                'appid' => $this->appId,
                'secret' => $this->appSecret,
                'js_code' => $code,
                'grant_type' => 'authorization_code'
            ]);

            if (isset($this->response_body['errcode'])) {
                throw new WechatAPIException(['method_name' => __METHOD__, 'args' => func_get_args()], $this->response_body['errmsg'], $this->response_body['errcode']);
            }

            return json_decode($this->response_body->body(), true);
        } catch (RequestException $exception) {
            throw new WechatAPIException(['method_name' => __METHOD__, 'args' => func_get_args()], $exception->getMessage(), $exception->getCode());
        }
    }
}
