<?php

namespace App\Services\ShortMessage;

use App\Facades\WorkWeixin;

class Login implements SuperModuleInterface
{
    public function send(string $prefix, string $phone, string $message): void
    {
        $this->aliyun($prefix, $phone, $message);

        $this->workwx($phone, $message);
    }

    private function aliyun($prefix, $phone, $message)
    {
        (new \App\Services\Aliyun\ShortMessage('dysmsapi.aliyuncs.com'))->sendLoginCode($prefix, $phone, $message);
    }

    private function workwx($phone, $message)
    {
        $user_id = WorkWeixin::channel('work_wx.user')->setCorp('default')->getUserIdByMobile($phone);

        return WorkWeixin::channel('work_wx.appliance')->setCorp('default')->setToUser([$user_id])->sendText('您的验证码为: ' . $message);
    }
}
