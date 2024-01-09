<?php

namespace App\Services\WorkWeixin;

use App\Exceptions\WorkWeixinAPIException;
use App\Services\HttpTrait;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Redis;

class Base
{
    use HttpTrait;

    private $base_url = 'https://qyapi.weixin.qq.com/cgi-bin';

    public $corp_config;

    public $access_token;

    public $has_access_token = false;

    protected function getToken($corp): void
    {
        $this->corp_config = $this->getCorpConfig($corp);

        if ($this->has_access_token) {
            $this->access_token = $this->getRedisValue();
        }

        if (is_null($this->access_token)) {
            $this->has_access_token = false;

            try {
                $this->getHttp($this->base_url . '/gettoken', [
                    'corpid' => config('services.work_weixin.corpid'),
                    'corpsecret' => $this->corp_config['secret']
                ]);

                if (0 !== $this->response_body['errcode']) {
                    throw new WorkWeixinAPIException(['method_name' => __METHOD__], $this->response_body['errmsg'], $this->response_body['errcode']);
                }

                $this->access_token = $this->setRedisValue(json_decode($this->response_body->body(), true));
            } catch (RequestException $exception) {
                throw new WorkWeixinAPIException(['method_name' => __METHOD__, 'args' => func_get_args()], $exception->getMessage(), $exception->getCode());
            }
        }
    }

    protected function getUserId($mobile)
    {
        $data = compact('mobile');

        $response = $this->send('/user/getuserid', $data);

        return $response['userid'];
    }

    protected function sendText($content, $to_user = '@all', $safe = 0): void
    {
        $data = [
            'touser' => $to_user,
            'msgtype' => "text",
            'agentid' => $this->corp_config['agentid'],
            'text' => ["content" => $content],
            'safe' => $safe
        ];

        $response = $this->send('/user/getuserid', $data);
//        $response = $this->send('/message/send', $data);
    }

    protected function sendMarkdown($content, $to_user = '@all', $safe = 0)
    {
        $data = [
            'touser' => $to_user,
            'msgtype' => "markdown",
            'agentid' => $this->corp_config['agentid'],
            'markdown' => ["content" => $content],
            'safe' => $safe
        ];

        $response = $this->send('/message/send', $data);
    }

    protected function getCorpConfig($corp)
    {
        return config('services.work_weixin_corp.' . $corp) ?? $this->getDefaultCorpConfig();
    }

    protected function getDefaultCorpConfig()
    {
        return config('services.work_weixin_corp.' . $this->getDefaultCorp());
    }

    protected function getDefaultCorp()
    {
        return config('services.work_weixin.corp');
    }

    protected function getRedisValue()
    {
        return Redis::get('work_weixin_corp_access_token_' . $this->corp_config['agentid']);
    }

    protected function setRedisValue($result)
    {
        $this->has_access_token = true;

        Redis::Setex('work_weixin_corp_access_token_' . $this->corp_config['agentid'], $result['expires_in'], $result['access_token']);

        return $result['access_token'];
    }

    private function send($uri, $data)
    {
        try {
            $this->postHttp($this->base_url . $uri . '?access_token=' . $this->access_token, $data);

            if (0 !== $this->response_body['errcode']) {
                throw new WorkWeixinAPIException(['method_name' => __METHOD__], $this->response_body['errmsg'], $this->response_body['errcode']);
            }

            return json_decode($this->response_body->body(), true);
        } catch (RequestException $exception) {
            throw new WorkWeixinAPIException(['method_name' => __METHOD__, 'args' => func_get_args()], $exception->getMessage(), $exception->getCode());
        }
    }
}
