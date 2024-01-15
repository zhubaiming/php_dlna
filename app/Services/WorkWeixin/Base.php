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

    protected $corp;

    private $access_token;

    public function setCorp($corp)
    {
        $this->corp = config('services.work_weixin_corp.' . $corp) ?? $this->getDefaultCorpConfig();

        return $this;
    }

    protected function getDefaultCorpConfig()
    {
        return config('services.work_weixin_corp.' . $this->getDefaultCorp());
    }

    protected function getDefaultCorp()
    {
        return config('services.work_weixin.corp');
    }

    protected function getCorpConfig($corp)
    {
        return config('services.work_weixin_corp.' . $corp) ?? $this->getDefaultCorpConfig();
    }

    protected function getAgentId()
    {
        return $this->corp['agentid'];
    }

    protected function getToken()
    {
        $this->access_token = $this->getRedisValue();

        if (is_null($this->access_token)) {
            $response = $this->sendGet('/gettoken', [
                'corpid' => config('services.work_weixin.corpid'),
                'corpsecret' => $this->corp['secret']
            ], false);

            $this->access_token = $this->setRedisValue($response);
        }
    }

    private function getRedisValue()
    {
        return Redis::get('work_weixin_corp_access_token_' . $this->getAgentId());
    }

    private function setRedisValue($result)
    {
        Redis::Setex('work_weixin_corp_access_token_' . $this->getAgentId(), $result['expires_in'], $result['access_token']);

        return $result['access_token'];
    }

    protected function sendGet($uri, $data, $has_token = true, $debug = false)
    {
        $url = $this->base_url . $uri;

        if ($has_token) {
            $this->getToken();
            $url .= '?access_token=' . $this->access_token;
        }

        if ('local' === config('app.env') && $debug) $url .= '&debug=1';

        $response = $this->getHttp($url, $data);

        return $this->checkResponse($response);
    }

    protected function sendPost($uri, $data, $has_token = true, $debug = false, $as = 'json')
    {
        $url = $this->base_url . $uri;

        if ($has_token) {
            $this->getToken();
            $url .= '?access_token=' . $this->access_token;
        }

        if ('local' === config('app.env') && $debug) $url .= '&debug=1';

        $response = $this->postHttp($url, $data, $as);

        return $this->checkResponse($response);
    }

    private function checkResponse($response)
    {
        if (0 !== $response['errcode']) {
            throw new WorkWeixinAPIException(['method_name' => __METHOD__], $response['errmsg'], $response['errcode']);
        }

        return $response;
    }

    private function send($uri, $data)
    {
        try {
            $url = $this->base_url . $uri . '?access_token=' . $this->access_token;

            if ('local' === config('app.env')) $url .= '&debug=1';

            $response = $this->postHttp($url, $data);

            if (0 !== $response['errcode']) {
                throw new WorkWeixinAPIException(['method_name' => __METHOD__], $response['errmsg'], $response['errcode']);
            }

            return $response;
        } catch (RequestException $exception) {
            throw new WorkWeixinAPIException(['method_name' => __METHOD__, 'args' => func_get_args()], $exception->getMessage(), $exception->getCode());
        }
    }


}
