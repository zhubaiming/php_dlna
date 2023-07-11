<?php

namespace App\Http\Resources\wechatMini;

use Illuminate\Http\Request;

class MediaCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
