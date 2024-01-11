<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaCateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->is('*/wechatMiniApp/*')) {
            $return = [
                'id' => $this->id,
                'name'=>$this->name,
                'child'=>self::collection($this->withManyChild)
            ];
        }

        return $return;


//        dd($request->is('*/wechatMiniApp/*'), $request->path(), $request, $request->route(), $request->getUri());
//        return [
//            'id' => $this->id,
//            'name' => $this->name,
//            'child' => self::collection($this->withChild)
//        ];
    }
}
