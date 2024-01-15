<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_infos_20240111')->orderBy('id')->lazy()->each(function (object $user) {
            $credentials = [
                'verificationCode' => '123456',
                'country_code' => $user->country_code,
                'pure_phone_number' => $user->pure_phone_number,
                'wxLoginCode' => '123456',
                'app_id' => config('services.wechat.mini_app.app_id'),
                'device_model' => $user->device_model,
                'device_system' => $user->device_system,
                'wechat_SDK_version' => $user->wechat_SDK_version,
                'wechat_language' => $user->wechat_language,
                'wechat_version' => $user->wechat_version,
                'ip' => $user->last_login_ip
            ];

            Auth::guard('wechat')->register($credentials);
        });
    }
}
