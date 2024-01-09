<?php

namespace App\Providers;

use App\Services\ShortMessage\Login;
use App\Services\ShortMessage\ShortMessage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ShortMessageServiceProvider extends ServiceProvider
{
    /**
     * 所有需要注册的容器单例
     *
     * @var array
     */
    public $singletons = [
        'sms.login' => Login::class
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        /*
         * 简单绑定
         * $this->app->bind();
         */

//        $this->app->bind('short_message', function (Application $app, $moduleName = 'sms.login') {
//            return new ShortMessage($app->make($moduleName));
//        });

        $this->app->bind('short_message', function (Application $app) {
            return new ShortMessage($app);
        });

        // 使用: $this->app->make('short_message','sms.login');

        /*
         * 单例绑定
         * $this->app->singleton();
         *
         * 将类或接口绑定到只应解析一次的容器中
         * 解析单例绑定后，后续调用容器时将返回相同的对象实例
         */

        /*
         * 绑定作用域单例
         * $this->app->scoped();
         *
         * 将一个类或接口绑定到容器中，该容器只应在给定的 Laravel 作业/请求作业周期内解析一次
         * 虽然该方法与 singleton 类似，但是当 Laravel 应用程序开始一个新的「生命周期」时，使用 scoped 方法注册的实例将被刷新
         */

        /*
         * 绑定实例
         * $this->app->instance();
         *
         * 将一个现有的对象实例绑定到容器中
         * 给定的实例总会在后续对容器的调用中返回
         */

        /*
         * 继承绑定
         * $this->app->extend();
         *
         * 允许修改已解析的服务
         * 例如
         * 解析服务时，可以运行其他代码来修饰或配置服务
         * 接受闭包，该闭包应返回修改后的服务作为其唯一参数
         */
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
