<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WorkWeixin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'work_weixin';
    }
}
