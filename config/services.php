<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'wechat' => [
        'mini_app' => [
            'app_id' => env('WX_MINIAPP_APP_ID'),
            'app_secret' => env('WX_MINIAPP_APP_SECRET'),
        ],
    ],

    'aliyun' => [
        'accesskey_id' => env('ALY_ACCESSKEY_ID'),
        'accesskey_secret' => env('ALY_ACCESSKEY_SECRET'),
        'dysms' => [
            'sign_name' => 'local' === env('APP_ENV') ? '阿里云短信测试' : env('ALY_DYSMS_SIGN_NAME'),
            'template_code' => env('ALY_DYSMS_TEMPLATE_CODE'),
            'login_template_code' => 'local' === env('APP_ENV') ? 'SMS_154950909' : env('ALY_DYSMS_LOGIN_TEMPLATE_CODE'),
        ]
    ],

    'work_weixin' => [
        'corpid' => env('WORK_WX_CORPID'),
        'corp' => 'default',
        'rob' => [
            'exception' => env('WORK_WEIXIN_ROB_EXCEPTION_KEY')
        ]
    ],

    'work_weixin_corp' => [
        'default' => [
            'agentid' => env('WORK_WEIXIN_CORP_DEFAULT_AGENTID'),
            'secret' => env('WORK_WEIXIN_CORP_DEFAULT_SECRET')
        ]
    ],
];
