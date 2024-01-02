<?php

namespace App\Providers;

use App\Services\Auth\ApiGuard;
use App\Services\Auth\PhoneVerifyCodeUserProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * 注册任意应用程序验证 / 授权服务
     */
    public function boot(): void
    {
        Auth::extend('jwt', function (Application $app, string $name, array $config) {
            // 返回 Illuminate\Contracts\Auth\Guard 的实例
            return ApiGuard::setClass($name, Auth::createUserProvider($config['provider']));
        });

        Auth::provider('phone-verify-Code', function (Application $app, array $config) {
            return new PhoneVerifyCodeUserProvider($app['hash'], $config['model']);
        });
    }
}
