<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\HttpCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

trait WechatTrait
{
    protected function wechatLogin($input): \Illuminate\Http\JsonResponse
    {
        if (!$this->validateRequest($input)) {
            return $this->failed($this->validateErrorMessage, HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (
            Auth::guard('wechat')->attempt(['country_code' => $this->validateSafeAll['prefix'], 'pure_phone_number' => $this->validateSafeAll['phoneNumber'], 'verificationCode' => $this->validateSafeAll['verificationCode']]) ||
            Auth::guard('wechat')->register($this->validateSafeAll)
        ) {
            return $this->success(Auth::guard('wechat')->user()->token);
        } else {
            return $this->failed('用户登录失败', HttpCode::HTTP_UNAUTHORIZED);
        }
    }

    protected function validateRequest($input): bool
    {
        $validator = Validator::make($input, [
            'prefix' => ['required', 'string'],
            'phoneNumber' => ['regex:/^[1]([3]\d|[4][5|7]|[5][0-3|5-9]|[6][6]|[7][3|5-8]|[8][1-9]|[9][0-1|3|5-9])\d{8}/i', 'required', 'size:11', 'string'],
            'verificationCode' => ['required', 'size:6', 'string'],
            'wxLoginCode' => ['required', 'string'],
            'systemInfo' => ['array', 'required']
        ], [
            'prefix.required' => '手机号前缀必须填写',
            'prefix.string' => '手机号前缀类型错误',
            'phoneNumber.regex' => '手机号格式错误',
            'phoneNumber.required' => '手机号必须填写',
            'phoneNumber.size' => '手机号长度错误',
            'phoneNumber.string' => '手机号类型错误',
            'verificationCode.required' => '验证码必须填写',
            'verificationCode.size' => '验证码长度错误',
            'verificationCode.string' => '验证码类型错误',
            'wxLoginCode.required' => 'code必须填写',
            'wxLoginCode.string' => 'code类型错误',
            'systemInfo.array' => '系统信息类型错误',
            'systemInfo.required' => '系统信息必须填写',
        ]);


        if ($validator->stopOnFirstFailure()->fails()) {
            $this->validateErrorMessage = $validator->errors();
            return false;
        } else {
            $this->validateSafeAll = $validator->validated();
            return true;
        }
    }
}
