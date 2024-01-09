<?php

namespace App\Services\Aliyun;

use App\Exceptions\AliyunAPIException;
use \Exception;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Darabonba\OpenApi\Models\Config;
use Illuminate\Support\Facades\Log;

class Base
{
    private $accessKeyId;

    private $accessKeySecret;

    private $endpoint;

    private $signName;

    private $templateCode;

    private $sendSmsRequest;

    private $runtime;

    public function __construct(string $endpoint)
    {
        $this->accessKeyId = config('services.aliyun.accesskey_id');

        $this->accessKeySecret = config('services.aliyun.accesskey_secret');

        $this->endpoint = $endpoint;

        $this->signName = config('services.aliyun.dysms.sign_name');
    }

    protected function setConfig(): Config
    {
        $config = new Config([
            'accessKeyId' => $this->accessKeyId,
            'accessKeySecret' => $this->accessKeySecret
        ]);

        $config->endpoint = $this->endpoint;

        return $config;
    }

    protected function getSignName()
    {
        return $this->signName;
    }

    protected function getDefaultTemplateCode()
    {
        return config('services.aliyun.dysms.template_code');
    }

    protected function setTemplateCode($template_name): void
    {
        $this->templateCode = config('services.aliyun.dysms.' . $template_name) ?? $this->getDefaultTemplateCode();
    }

    protected function getTemplateCode()
    {
        return $this->templateCode;
    }

    protected function assembleSendSmsRequest($phone, $template_param): void
    {
        $this->sendSmsRequest = new SendSmsRequest([
            'phoneNumbers' => $phone,
            'signName' => $this->getSignName(),
            'templateCode' => $this->getTemplateCode(),
            'templateParam' => json_encode($template_param, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES + JSON_NUMERIC_CHECK)
        ]);
    }

    protected function assembleRuntimeOptions(): void
    {
        $this->runtime = new RuntimeOptions([
//            'autoretry' => true,
//            'maxAttempts' => 3,
            'readTimeout' => 10000,
            'connectTimeout' => 10000,
//            'maxIdleConns' => 3,
        ]);
    }

    protected function assembleSendSmsWithOptions(): void
    {
        $config = $this->setConfig();

        $client = new Dysmsapi($config);

        $this->assembleRuntimeOptions();

        try {
            $response = $client->sendSmsWithOptions($this->sendSmsRequest, $this->runtime);

            if ('local' === config('app.env')) {
                Log::channel('aliyunApi')->debug('[debug] - ' . date('Y-m-d H:i:s') . ' - send success', $response->toMap());
            }

//            return $client->sendSmsWithOptions($this->sendSmsRequest, $this->runtime);
//
//             var_dump($response->toMap());
//
//            // 调用 response->Body 中对应的属性获得返回的参数值
//             var_dump($response->Body->requestId);
        } catch (Exception $exception) {
            $response = $exception->getErrorInfo();
            throw new AliyunAPIException($exception->getLastRequest()->query, $response['code'] . ': ' . $response['data']['Message'], $response['data']['statusCode']);

//            dd($exception->getMessage(), $exception->getErrorInfo(), $exception->getLastException(), $exception->getLastRequest());

            // 获取报错信息
//            var_dump($exception->getMessage());
            // 获取报错数
//            var_dump($exception->getErrorInfo());
            // 获取最后一次报错的 Exception 实例
//            var_dump($exception->getLastException());
            // 获取最后一次请求的 Request 实例
//            var_dump($exception->getLastRequest());
//            var_dump($exception->code);
//            var_dump($exception->message);
//            var_dump($exception->data);
        }
    }
}
