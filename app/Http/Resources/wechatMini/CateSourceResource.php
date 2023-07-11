<?php

namespace App\Http\Resources\wechatMini;

use Illuminate\Http\Request;

class CateSourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'templateName' => match ($this->pid) {
                1 => 'custom-media-video-list',
                2 => 'custom-media-audio-list',
                3 => 'custom-media-image-list'
            },
            'cate_id' => $this->id,
            'name' => $this->name
        ];
    }
}
