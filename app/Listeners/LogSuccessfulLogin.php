<?php

namespace App\Listeners;

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
        DB::table('log_user_login')->insert([
            'user_id' => $event->user->user_id,
            'created_at' => date('Y-m-d H:i:s', time()),
            'ip_address' => app('request')->ip(),
            'status' => 1,
            'login_guard' => $event->guard
        ]);

        Redis::del('weixin_login_sms_' . $event->user->country_code . '_' . $event->user->pure_phone_number);
    }
}
