<?php

namespace App\Http\Requests\Api\Video;

use App\Http\Requests\Api\FormRequest;

class IndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            //
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
