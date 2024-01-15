<?php

namespace App\Exceptions;

use App\Facades\WorkWeixin;
use Exception;

class BaseException extends Exception
{
    protected array $primitives;

    public function __construct(array $primitives, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = $message;

        $this->code = $code;

        $this->primitives = $primitives;

        if (!($previous instanceof WorkWeixinAPIException)) {
            WorkWeixin::channel('work_wx.rob')->setKey('exception')->sendMarkdown(
                "## 当前发生异常  \n  \n>**异常来源**: **<font color=\"warning\">" . static::class . "</font>**  \n>  \n>**异常状态码**: **<font color=\"warning\">" . $code . "</font>**  \n>  \n>**异常内容**:  \n>  \n>" . $message
            );
        }
    }

    public function getPrimitives()
    {
        return $this->primitives;
    }
}
