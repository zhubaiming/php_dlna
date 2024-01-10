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

    private $corp_config;

    private $access_token;

    private $has_access_token = false;

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

    /**
     * 选择获取用户id方式
     *
     * @param $type
     * @return bool|string
     */
    protected function chooseGetUserIdType($type): bool|string
    {
        return match ($type) {
            'mobile' => 'getUserIdByMobile',
            'email' => 'getUserIdByEmail',
            default => false,
        };
    }

    /**
     * 手机号获取userid
     *
     * @param $mobile    用户在企业微信通讯录中的手机号码。长度为5～32个字节
     * @return mixed
     * @throws WorkWeixinAPIException
     */
    protected function getUserIdByMobile($mobile)
    {
        $data = compact('mobile');

        $response = $this->send('/user/getuserid', $data);

        return $response['userid'];
    }

    /**
     * 邮箱获取 userid
     *
     * @param $email        邮箱
     * @param $email_type   邮箱类型：1-企业邮箱（默认）；2-个人邮箱
     * @return mixed
     * @throws WorkWeixinAPIException
     */
    protected function getUserIdByEmail($email, $email_type = 1)
    {
        $data = compact('email', 'email_type');

        $response = $this->send('/user/get_userid_by_email', $data);

        return $response['userid'];
    }

    protected function sendText($content, $to_user = '@all', $safe = 0): void
    {
        if (is_array($to_user)) $to_user = implode('|', $to_user);

        $data = [
            'touser' => $to_user,
            'msgtype' => 'text',
            'agentid' => $this->corp_config['agentid'],
            'text' => ['content' => $content],
            'safe' => $safe
        ];

        $response = $this->send('/message/send', $data);
    }

    protected function sendMarkdown($content, $to_user = '@all', $safe = 0): void
    {
        if (is_array($to_user)) $to_user = implode('|', $to_user);

        $data = [
            'touser' => $to_user,
            'msgtype' => 'markdown',
            'agentid' => $this->corp_config['agentid'],
            'markdown' => ['content' => $content],
            'safe' => $safe
        ];

        $response = $this->send('/message/send', $data);
    }

    protected function sendNews($content, $to_user = '@all', $safe = 0): void
    {
        if (is_array($to_user)) $to_user = implode('|', $to_user);

        $data = [
            'touser' => $to_user,
            'msgtype' => 'news',
            'agentid' => $this->corp_config['agentid'],
            'news' => ['articles' => $content],
            'safe' => $safe
        ];

        $response = $this->send('/message/send', $data);
    }

    protected function sendMiniprogramNotice($content, $to_user)
    {
        $content['appid'] = config('services.wechat.mini_app.app_id');

        $to_user = implode('|', $to_user);

        $data = [
            'touser' => $to_user,
            'msgtype' => 'miniprogram_notice',
            'miniprogram_notice' => $content
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
