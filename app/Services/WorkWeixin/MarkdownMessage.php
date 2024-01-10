<?php

namespace App\Services\WorkWeixin;

class MarkdownMessage extends Base
{
    public function sendException(string $type, string $column, string $exception_origin, $code, $message)
    {
        $this->getToken('default');

        $func = $this->chooseGetUserIdType($type);

        if ($func) {
            $user_id = $this->{$func}($column);
        } else {
            $user_id = $column;
        }

        $this->sendMarkdown("## 当前发生异常  \n  \n>**异常来源**: **<font color=\"warning\">" . $exception_origin . "</font>**  \n>  \n>**异常状态码**: **<font color=\"warning\">" . $code . "</font>**  \n>  \n>**异常内容**:  \n>  \n>" . $message, $user_id);
    }
}
