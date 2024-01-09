<?php

namespace App\Services\Aliyun;

class ShortMessage extends Base
{
    public function sendLoginCode(string $prefix, string $phone, array $template_param): void
    {
        $this->setTemplateCode('login_template_code');

        $this->assembleSendSmsRequest($prefix . $phone, $template_param);

        $this->assembleSendSmsWithOptions();
    }
}
