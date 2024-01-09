<?php

namespace App\Services\WorkWeixin;

class TextMessage extends Base
{
    public function sendLoginCode(string $prefix, string $phone, array $template_param)
    {
        $this->getToken('default');

        $user_id = $this->getUserId($phone);

        $this->sendText('您的验证码为: ' . $template_param['code'], $user_id);
    }
}
