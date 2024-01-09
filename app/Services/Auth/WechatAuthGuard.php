<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\MistID;
use App\Services\Weixin\MiniApp;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class WechatAuthGuard implements StatefulGuard
{
    use AuthHelpers;

    private string $redis_key_prefix = 'User_login_';

    /**
     * @inheritDoc
     */
    public function check()
    {
        // TODO: Implement check() method.
    }

    /**
     * @inheritDoc
     */
    public function guest()
    {
        // TODO: Implement guest() method.
    }

    /**
     * Get the currently authenticated user.
     *
     * @return Authenticatable|void|null
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

    /**
     * @inheritDoc
     */
    public function id()
    {
        // TODO: Implement id() method.
    }

    /**
     * @inheritDoc
     */
    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }

    /**
     * @inheritDoc
     */
    public function hasUser(): bool
    {
        if (is_null($this->user)) return false;

        return 1 === Redis::exists($this->redis_key_prefix . md5($this->user()->id));
    }


    /**
     * @inheritDoc
     */
    public function once(array $credentials = [])
    {
        // TODO: Implement once() method.
    }

    /**
     * @param Authenticatable $user
     * @param $remember
     * @return void
     */
    public function login(Authenticatable $user, $remember = false): void
    {
        // 1、获取 token
        $this->token = app()->make('tymon.jwt')->fromUser($user);

        // 2、创建或更新redis信息
        $this->updateToken($user);

        // 3、执行通用
        $this->loginBase($user, $remember);
    }

    /**
     * @inheritDoc
     */
    public function loginUsingId($id, $remember = false)
    {
        // TODO: Implement loginUsingId() method.
    }

    /**
     * @inheritDoc
     */
    public function onceUsingId($id)
    {
        // TODO: Implement onceUsingId() method.
    }

    /**
     * @inheritDoc
     */
    public function viaRemember()
    {
        // TODO: Implement viaRemember() method.
    }

    /**
     * @inheritDoc
     */
    public function logout()
    {
        // TODO: Implement logout() method.
    }

    /**(无状态 - 意味着是无论在控制器方法、命令行、测试代码中，皆可调用)
     * @param array $credentials
     * @param $remember
     * @return bool
     * @throws \App\Exceptions\WechatAPIException
     */
    public function register(array $credentials = [], $remember = false): bool
    {
        // 1、解析微信code
        if ($this->validateWechatCode($credentials)) {
            return false;
        }
        $wechat_session = MiniApp::jscodeToSession([$credentials['wxLoginCode']]);

        // 2、创建用户
        $user = $this->createUser(array_merge($credentials, $wechat_session));

        // 3、验证用户验证码
        if ($this->hasValidCredentials($user, $credentials)) {

            // 4、触发注册事件
            $this->fireRegisterEvent($user);

            // 5、追加$user数据
            $user = $this->appendUserResource($user, $credentials);

            $this->login($user, $remember);

            return true;
        }

        return false;
    }

    /**
     * @param $user
     * @return void
     */
    private function updateToken($user): void
    {
        $user->last_token = $this->token;

//        Redis::Hmset('User_login_' . md5($user->id), $user->toArray());
        Redis::Setex($this->redis_key_prefix . md5($user->id), 7200, base64_encode(gzcompress(serialize($user))));
    }

    private function validateWechatCode($credentials): bool
    {
        return is_null($credentials['wxLoginCode']);
    }

    private function createUser($credentials): User
    {
        $user = new User();

        $user->user_id = MistID::generate($credentials['pure_phone_number']);

        $user->account = $credentials['pure_phone_number'];
        $user->password = Hash::make('123456');

        $user->country_code = $credentials['country_code'];
        $user->pure_phone_number = $credentials['pure_phone_number'];
        $user->origin = '微信小程序';
        $user->app_id = $credentials['app_id'];

        $user->wechat_openid = $credentials['openid'];
        $user->wechat_unionid = $credentials['unionid'] ?? null;

        $user->sing_in_at = date('Y-m-d H:i:s', $this->time);

        return $user;
    }
}
