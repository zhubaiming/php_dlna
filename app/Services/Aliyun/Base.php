<?php

namespace App\Services\Aliyun;

use Darabonba\OpenApi\Models\Config;

class Base
{
    protected $confog;

    public function __construct()
    {
        $this->confog = new Config([
//            'accessKeyId' => env('ALY_ACCESSKEY_ID'),
            'accessKeyId' => 'LTAI5t9cntcy6zMWLpwYi2Cp',
//            'accessKeySecret' => env('ALY_ACCESSKEY_SECRET')
            'accessKeySecret' => 'JJZ3tvITBEie3eErrfvK2DQ4pmSCP7'
        ]);

        $this->confog->endpoint = 'dysmsapi.aliyuncs.com';
    }
}
