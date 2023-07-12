<?php

namespace App\Http\Resources\wechatMini;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

class JsonResource extends BaseJsonResource
{
    public static $wrap = 'data';

    protected static function newCollection($resource)
    {
        return new ResourceCollection($resource, static::class);
    }

    public function with(Request $request)
    {
        return [
            'code' => 0,
            'msg' => 'ok'
        ];
    }
}
