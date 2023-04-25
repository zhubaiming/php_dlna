<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $data = [
        'title' => '当前运行项目框架配置信息显示',
        'info' => [
            'env' => [
                'file' => file_exists(base_path('.env')) ? '.env文件' : '',
                'app' => [
                    'app_name' => env('APP_NAME'),
                    'app_env' => env('APP_ENV'),
                    'app_key' => env('APP_KEY'),
                    'app_debug' => env('APP_DEBUG'),
                    'app_url' => env('APP_URL')
                ],
                'log' => [
                    'log_channel' => env('LOG_CHANNEL'),
                    'log_deprecations_channel' => env('LOG_DEPRECATIONS_CHANNEL'),
                    'log_level' => env('LOG_LEVEL')
                ],
                'db' => [
                    'db_connection' => env('DB_CONNECTION'),
                    'db_host' => env('DB_HOST'),
                    'db_port' => env('DB_PORT'),
                    'db_database' => env('DB_DATABASE'),
                    'db_username' => env('DB_USERNAME'),
                    'db_password' => env('DB_PASSWORD')
                ],
                'redis' => [
                    'redis_host' => env('REDIS_HOST'),
                    'redis_password' => env('REDIS_PASSWORD'),
                    'redis_port' => env('REDIS_PORT')
                ],
            ],
            'config' => [
                'app' => [
                    'file' => 'config/app.php 文件',
                    'name' => config('app.name'),
                    'env' => config('app.env'),
                    'debug' => config('app.debug'),
                    'url' => config('app.url'),
                    'asset_url' => config('app.asset_url'),
                    'timezone' => config('app.timezone'),
                    'locale' => config('app.locale'),
                    'fallback_locale' => config('app.fallback_locale'),
                    'faker_locale' => config('app.faker_locale'),
                    'key' => config('app.key'),
                    'cipher' => config('app.cipher'),
//                    'maintenance' => config('app.maintenance')
                ]
            ]
        ]
    ];
    return view('welcome', ['data' => $data]);
});
