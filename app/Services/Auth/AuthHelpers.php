<?php

namespace App\Services\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

trait AuthHelpers
{
    protected $app;

    protected $name;

    protected $provider;

    protected $token;

    protected $user;


    protected $time;

    protected $events;

    protected $request;

    public function __construct(Application $app, $name, UserProvider $provider)
    {
        $this->app = $app;

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

    /**
     * @inheritDoc
     */
    public function check()
    {
        try {
            $jwt = $this->app->make('tymon.jwt')->parseToken();

            if ($this->onceUsingId($jwt->getPayload()->get('sub')) && $jwt->getToken()->get() === $this->user()->last_token) {
                return true;
            }

            return false;
        } catch (JWTException $exception) {
            throw new UnauthorizedHttpException('auth', $exception->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function onceUsingId($id): bool
    {
        if (!is_null($user = $this->provider->retrieveById($id))) {

            $this->setUser($user);

            return true;
        }

        return false;
    }

    /**
     * 获取当前已通过身份验证的用户
     *
     * @return void
     */
    public function user()
    {
        //if ($this->loggedOut) {
        //            return;
        //        }
        //
        //        // 如果我们已经为当前请求获取了用户数据，则可以立即返回。
        //        // 我们不想在每次调用该方法时都获取用户数据，因为那样会非常慢。
        //        if (! is_null($this->user)) {
        //            return $this->user;
        //        }
        //
        //        $id = $this->session->get($this->getName());
        //
        //        // 首先，如果会话中存在标识符，我们将尝试使用该标识符加载用户。
        //        // 否则，我们将检查该请求中是否存在 "记住我 "cookie，如果存在，则尝试使用该cookie检索用户。
        //        if (! is_null($id) && $this->user = $this->provider->retrieveById($id)) {
        //            $this->fireAuthenticatedEvent($this->user);
        //        }
        //
        //        // 如果用户为空，但我们解密了一个 "recaller "cookie，我们就可以尝试在该 cookie 上提取用户数据，该 cookie 将作为应用程序的记忆 cookie
        //        // 。一旦有了用户，我们就可以将其返回给调用者。
        //        if (is_null($this->user) && ! is_null($recaller = $this->recaller())) {
        //            $this->user = $this->userFromRecaller($recaller);
        //
        //            if ($this->user) {
        //                $this->updateSession($this->user->getAuthIdentifier());
        //
        //                $this->fireLoginEvent($this->user, true);
        //            }
        //        }
        //
        //        return $this->user;
        // TODO: Implement user() method.

        if (!is_null($this->user)) {
            return $this->user;
        }
    }

    public function refresh()
    {
        // $token = $this->auth->parseToken()->refresh();
        // $id = $this->app->make('tymon.jwt')->parseToken()->getPayload()->get('sub');

        $token = $this->app->make('tymon.jwt')->refresh();
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
