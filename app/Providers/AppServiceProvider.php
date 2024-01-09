<?php

namespace App\Providers;

use App\Services\Weixin\MiniApp;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        'wechat_mini_app' => MiniApp::class
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        /**
         * 【数据库】监听查询事件
         */
        DB::listen(function (QueryExecuted $query) {
            Log::channel('sql')->info('[info] - ' . date('Y-m-d H:i:s') . 'time: ' . $query->time . ' - sql: ' . $query->sql, $query->bindings);
        });
    }
}
