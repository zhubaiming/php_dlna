<?php

namespace App\Services\Aliyun;

class ShortMessage extends Base
{
    public function sendLoginCode(string $phone, array $template_param): void
    {
        $this->setTemplateCode('login_template_code');

        $this->assembleSendSmsRequest($phone, $template_param);

        $this->assembleSendSmsWithOptions();
    }
}
