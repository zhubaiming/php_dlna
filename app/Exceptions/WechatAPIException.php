<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;

class WechatAPIException extends BaseException
{
    private $error_messages = [
        40163 => 'code已经被使用',
        40029 => '无效的code',
        40066 => '无效的请求地址',
    ];

    /**
     * 获取异常上下文信息
     *
     * @return array
     */
//    public function context(): array
//    {
//        return $this->getPrimitives();
//    }

    /**
     * 报告或记录异常
     *
     * @return void
     */
    public function report()
    {
        $code = $this->getCode();
        Log::channel('wechatApi')->error('[error] - ' . date('Y-m-d H:i:s') . ' - ' . $code . ' - ' . $this->error_messages[$code] . '(' . $this->getMessage() . ')', $this->getPrimitives());
    }
}
