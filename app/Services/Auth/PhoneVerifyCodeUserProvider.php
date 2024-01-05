<?php

namespace App\Services\Auth;

use App\Enums\UserEnum;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class PhoneVerifyCodeUserProvider extends EloquentUserProvider
{
    private array $retrieveColumns = ['country_code', 'pure_phone_number'];

    /**
     * 根据给定字段获取用户
     *
     * @param array $credentials
     * @return UserContract|null
     */
    public function retrieveByCredentials(array $credentials): ?UserContract
    {
        $credentials = array_filter( // array_filter 使用回调函数过滤数组的元素，返回过滤后的数组，便利数组中的每个值，并将每个值传递给回调函数，如果回调函数返回 true，则将数组中的当前值返回到结果数组中，键名保持不变
            $credentials,
            fn($key) => in_array($key, $this->retrieveColumns),
//            fn($key) => !str_contains($key, 'verificationCode'), // str_contains 在表达式中搜索给定字符串中的子字符串，返回bool
            ARRAY_FILTER_USE_KEY // ARRAY_FILTER_USE_KEY - callback 接受键名作为的唯一参数，ARRAY_FILTER_USE_BOTH - callback 同时接受键名和键值
        );

        return parent::retrieveByCredentials($credentials);
    }

    /**
     * 验证给定用户的状态
     *
     * @param UserContract $user
     * @return bool
     * @throws \Exception
     */
    public function validateUserStatus(UserContract $user): bool
    {
        switch ($user->status) {
            case UserEnum::USER_NORMAL:
                return true;
            case UserEnum::USER_INVALID:
                return false;
            case UserEnum::USER_FREEZE:
            default:
                throw new \Exception();
        }
    }

    /**
     * 验证给定用户与给定数据是否匹配
     *
     * @param UserContract $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials): bool
    {
        if (is_null($plain = $credentials['verificationCode'])) {
            return false;
        }

        return 'local' === config('app.env') || $this->check($plain, $this->getAuthVerificationCode($user));
    }

    /**
     * 获取用户验证码
     *
     * @param $user
     * @return string
     */
    protected function getAuthVerificationCode($user): string
    {
        return Redis::get('weixin_login_sms_' . $user->country_code . '_' . $user->pure_phone_number) ?? Hash::make('123456');
    }

    /**
     * 验证两个给定值是否相等
     *
     * @param $value
     * @param $value_confirmed
     * @return bool
     */
    protected function check($value, $value_confirmed): bool
    {
        return $value === $value_confirmed;
    }
}
