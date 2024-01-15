<?php

namespace App\Providers;

use App\Services\WorkWeixin\Directory\User;
use App\Services\WorkWeixin\Message\Appliance;
use App\Services\WorkWeixin\Message\Rob;
use App\Services\WorkWeixin\WorkWeixin;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class WorkWeixinServiceProvider extends ServiceProvider
{

    public array $singletons = [
        'work_wx.user' => User::class,
        'work_wx.appliance' => Appliance::class,
        'work_wx.rob' => Rob::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('work_weixin', function (Application $app) {
            return new WorkWeixin($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
