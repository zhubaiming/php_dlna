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
        $arr = [];
        if ($request->is('*/mediaList')) {
            $arr = [
                'id' => $this->id,
                'name' => $this->name,
                'area' => $this->area,
                'year' => $this->year,
                'lang' => $this->lang,
                'coverPic' => $this->coverPic,
                'typeName' => $this->typeName,
                'source' => $this->source,
                'version' => $this->version,
                'status' => $this->status
            ];
        } elseif ($request->is('*/mediaDetail')) {
            $arr = [
                'name' => $this->name, //
                'area' => $this->area, //
                'year' => $this->year, //
                'lang' => $this->lang, //
                'coverPic' => $this->coverPic, //
                'typeName' => $this->typeName, //
                'director' => $this->director,
                'version' => $this->version, //
                'actor' => $this->actor, //
                'blurb' => $this->blurb, //
                'urls' => $this->urls, //
                'status' => $this->status //
            ];
        }

        return $arr;
    }
}
