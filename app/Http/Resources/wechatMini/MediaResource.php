<?php

namespace App\Http\Resources\wechatMini;

use Illuminate\Http\Request;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'area' => $this->area,
            'year' => $this->year,
            'lang' => $this->lang,
            'typeName' => $this->typeName,
            'source' => $this->source,
            'version' => $this->version,
            'coverPic' => $this->coverPic,
        ];
    }
}
