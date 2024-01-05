<?php

namespace App\Services\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Request;

trait AuthHelpers
{
    protected $user;

    protected $name;

    protected $provider;

    protected $time;

    protected $token;

    protected $events;

    protected $request;

    public function __construct($name, UserProvider $provider)
    {
        $this->name = $name;

        $this->provider = $provider;

        $this->setTime();
    }

    /**(无状态 - 意味着是无论在控制器方法、命令行、测试代码中，皆可调用)
     *
     * @param array $credentials
     * @param $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false): bool
    {
        // 通过 EloquentUserProvider 的 retrieveByCredentials 方法从用户表中查询出用户数据，通过 validateCredentials 方法来验证给定的用户认证数据与从用户表中查询出来的用户数据是否吻合
        $user = $this->provider->retrieveByCredentials($credentials);

        // 如果返回的是 UserInterface 的实现，我们将要求提供者根据给定的凭据验证用户，如果确实有效，我们将把用户登录到应用程序并返回 true。
        // 如果登录认证通过，通过 login 方法将用户对象装载到用用里去
        if ($this->hasValidCredentials($user, $credentials)) {
            // 追加$user数据
            $user = $this->appendUserResource($user, $credentials);

            $this->login($user, $remember);

            return true;
        }

        // 如果身份验证尝试失败，我们将触发一个事件，以便通知用户任何来自未识别用户的访问其账户的可疑尝试。开发人员可根据需要监听该事件。
        // 如果登录失败的话，可以触发事件通知用户有可疑的登录尝试(需要自己定义 listener 来实现)
//        $this->fireFailedEvent($user, $credentials);

        return false;
    }

    protected function loginBase($user, $remember): void
    {
        // 1、设置用户
        $this->setUser($user);

        // 2、触发登录事件
        // 如果我们设置了一个事件派发器实例，我们就会触发一个事件，这样任何监听器都会挂钩到身份验证事件，并根据从 guard 实例触发的登录和注销事件运行操作
        $this->fireLoginEvent($user, $remember);
    }

    /**
     * @param Authenticatable $user
     * @return $this
     */
    public function setUser(Authenticatable $user): static
    {
        $this->user = $user;

        //        $this->loggedOut = false;
        //
        //        $this->fireAuthenticatedEvent($user);

        return $this;
    }

    public function setDispatcher(Dispatcher $events): void
    {
        $this->events = $events;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param mixed $user
     * @param array $credentials
     * @return bool
     */
    protected function hasValidCredentials(mixed $user, array $credentials): bool
    {
        return !is_null($user) && $this->provider->validateUserStatus($user) && $this->provider->validateCredentials($user, $credentials);
    }

    /**
     * 注册成功事件
     *
     * @param $user
     * @return void
     */
    protected function fireRegisterEvent($user): void
    {
        $this->events?->dispatch(new Registered($user));
    }

    /**
     * 登录成功事件
     *
     * @param $user
     * @param $remember
     * @return void
     */
    protected function fireLoginEvent($user, $remember = false): void
    {
        $this->events?->dispatch(new Login($this->name, $user, $remember));
    }

    /**
     * 设置当前时间
     *
     * @return void
     */
    private function setTime(): void
    {
        $this->time = time();
    }

    private function appendUserResource($user, $credentials)
    {
        $credentials = array_filter( // array_filter 使用回调函数过滤数组的元素，返回过滤后的数组，便利数组中的每个值，并将每个值传递给回调函数，如果回调函数返回 true，则将数组中的当前值返回到结果数组中，键名保持不变
            $credentials,
            fn($key) => !str_contains($key, 'verificationCode') && !str_contains($key, 'wxLoginCode'), // str_contains 在表达式中搜索给定字符串中的子字符串，返回bool
            ARRAY_FILTER_USE_KEY // ARRAY_FILTER_USE_KEY - callback 接受键名作为的唯一参数，ARRAY_FILTER_USE_BOTH - callback 同时接受键名和键值
        );

        foreach ($credentials as $key => $value) {
            $user->$key = $user->$key ?? $value;
        }

        return $user;
    }

    private function getLastAttempted($id)
    {
        return 1 === Redis::exists($this->redis_key_prefix . md5($id)) ?
            Redis::get($this->redis_key_prefix . md5($id)) :
            null;
    }
}
