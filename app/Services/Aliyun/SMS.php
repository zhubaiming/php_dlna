<?php

namespace App\Services\Aliyun;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;

class SMS extends Base
{
    protected $client;

    public function __construct()
    {
        parent::__construct();

        $this->client = new Dysmsapi($this->confog);
    }

    protected function assembleSendSmsRequest($phone, $sing_name, $template_code, $template_param)
    {
        return new SendSmsRequest([
            'phoneNumbers' => $phone,
            'signName' => $sing_name,
            'templateCode' => $template_code,
            'templateParam' => json_encode($template_param, 256 + 64 + 32)
        ]);
    }

    protected function assembleRuntimeOptions()
    {
        return new RuntimeOptions([]);
    }

    protected function sendSms($sendSmsRequest, $runtime)
    {
        try {
            $response = $this->client->sendSmsWithOptions($sendSmsRequest, $runtime);

            // var_dump($response->toMap());

            // 调用 response->Body 中对应的属性获得返回的参数值
            // var_dump($response->Body->requestId);
        } catch (\Exception $e) {
            // 获取报错数
            var_dump($e->getErrorInfo());
            // 获取报错信息
            var_dump($e->getMessage());
            // 获取最后一次报错的 Exception 实例
            var_dump($e->getLastException());
            // 获取最后一次请求的 Request 实例
            var_dump($e->getLastRequest());
        }
    }

    public function toLogin($phone, $code)
    {
        $sing_name = '世纪网络科技';
        $template_code = 'SMS_172170143';

        $template_param = ['code' => $code];

        $sendSmsRequest = $this->assembleSendSmsRequest($phone, $sing_name, $template_code, $template_param);
        $runtime = $this->assembleRuntimeOptions();

        $this->sendSms($sendSmsRequest, $runtime);
    }
}
