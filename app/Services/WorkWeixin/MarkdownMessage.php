<?php

namespace App\Services\WorkWeixin;

class MarkdownMessage extends Base
{
    public function sendException(string $phone, string $exception_origin, $code, $message)
    {
        $this->getToken('default');

        $user_id = $this->getUserId($phone);

        $this->sendMarkdown("## 当前发生异常  \n  \n>**异常来源**: **<font color=\"warning\">" . $exception_origin . "</font>**  \n>  \n>**异常状态码**: **<font color=\"warning\">" . $code . "</font>**  \n>  \n>**异常内容**:  \n>  \n>" . $message, $user_id);
    }
}
