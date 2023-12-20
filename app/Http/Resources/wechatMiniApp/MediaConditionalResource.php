<?php

namespace App\Http\Resources\wechatMiniApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaConditionalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $res = [];

        if ($request->is('wechatMiniApp/*')) {
            $res = [
                'id' => $this->id,
                'name' => $this->name,
                'child' => MediaConditionalResource::collection($this->withChildren),
            ];
        }

        return $res;
    }
}
