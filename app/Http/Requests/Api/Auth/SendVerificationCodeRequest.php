<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\FormRequest;

class SendVerificationCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prefix' => ['required', 'string'],
            'phoneNumber' => ['regex:/^[1]([3]\d|[4][5|7]|[5][0-3|5-9]|[6][6]|[7][3|5-8]|[8][1-9]|[9][0-1|3|5-9])\d{8}/i', 'required', 'size:11', 'string']
        ];
    }

    public function messages()
    {
        return [
            'prefix.required' => '手机号前缀必须填写',
            'prefix.string' => '手机号前缀类型错误',
            'phoneNumber.regex' => '手机号格式错误',
            'phoneNumber.required' => '手机号必须填写',
            'phoneNumber.size' => '手机号长度错误',
            'phoneNumber.string' => '手机号类型错误'
        ];
    }
}
