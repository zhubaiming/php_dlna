<?php

namespace App\Services\Weixin;

use App\Exceptions\WechatAPIException;
use Illuminate\Http\Client\RequestException;

class MiniApp extends Base
{
    public function jscodeToSession($code)
    {
        try {
            $response = $this->getHttp('https://api.weixin.qq.com/sns/jscode2session', [
                'appid' => $this->appId,
                'secret' => $this->appSecret,
                'js_code' => $code,
                'grant_type' => 'authorization_code'
            ]);

            if (isset($response['errcode']) && 0 !== $response['errcode']) {
                throw new WechatAPIException(['method_name' => __METHOD__], $response['errmsg'], $response['errcode']);
            }

            return $response;
        } catch (RequestException $exception) {
            throw new WechatAPIException(['method_name' => __METHOD__, 'args' => func_get_args()], $exception->getMessage(), $exception->getCode());
        }
    }
}
