<?php

namespace App\Http\Resources\wechatMini;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseCollection;

class ResourceCollection extends BaseCollection
{
    public function paginationInformation($request, $paginated, $default): array
    {
        return [];
    }

    public function with(Request $request): array
    {
        return [
            'code' => 0,
            'msg' => 'ok'
        ];
    }
}
