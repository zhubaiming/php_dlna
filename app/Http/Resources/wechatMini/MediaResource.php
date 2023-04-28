<?php

namespace App\Http\Resources\wechatMini;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'area' => $this->area,
            'year' => $this->year,
            'lang' => $this->lang,
            'type' => $this->type,
            'source' => $this->source,
            'version' => $this->version,
            'coverPic' => $this->coverPic,
        ];
    }
}
