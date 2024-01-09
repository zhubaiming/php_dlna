<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;

class AliyunAPIException extends BaseException
{
    public function report()
    {
        Log::channel('aliyunApi')->error('[error] - ' . date('Y-m-d H:i:s') . ' - ' . $this->getCode() . ' - ' . $this->getMessage(), $this->getPrimitives());
    }
}
