<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;

class WechatAPIException extends BaseException
{
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
        Log::channel('wechatApi')->error('[error] - ' . date('Y-m-d H:i:s') . ' - ' . $this->getCode() . ' - ' . $this->getMessage(), $this->getPrimitives());
    }
}
