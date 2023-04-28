<?php

namespace App\Http\Resources\wechatMini;

use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

class JsonResource extends BaseJsonResource
{
    public static $wrap = 'list';

    protected static function newCollection($resource)
    {
        return new ResourceCollection($resource, static::class);
    }
}
