<?php

namespace App\Listeners;

use App\Enums\UserEnum;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // 更新用户最后登录时间和登录地址
        DB::table('log_user_login_infos')->insert([
            'user_id' => $event->user->user_id,
            'created_at' => date('Y-m-d H:i:s'),
            'ip_address' => $event->user->ip,
            'status' => UserEnum::LOGIN_SUCCESS,
            'login_guard' => $event->guard,
            'device_model' => $event->user->device_model ?? null,
            'device_system' => $event->user->device_system ?? null,
            'wechat_SDK_version' => $event->user->wechat_SDK_version ?? null,
            'wechat_language' => $event->user->wechat_language ?? null,
            'wechat_version' => $event->user->wechat_version ?? null,
        ]);

        Redis::del('weixin_login_sms_' . $event->user->country_code . '_' . $event->user->pure_phone_number);
    }
}
