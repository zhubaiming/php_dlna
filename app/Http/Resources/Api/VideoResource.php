<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->is('*/wechatMiniApp/*')) {

            if (strpos($request->route()->action['as'], 'index')) {
                $return = [
                    'id' => $this->id,
                    'origin' => $this->origin,
                    'type' => $this->type_name,
                    'area' => $this->area_name,
                    'name' => $this->name,
                    'cover_pic' => $this->cover_pic,
                    'year' => $this->year,
                    'lang' => $this->lang
                ];
            }

            if (strpos($request->route()->action['as'], 'show')) {
                $return = [
                    'id' => $this->id,
                    'origin' => $this->origin,
                    'type' => $this->type_name,
                    'area' => $this->area_name,
                    'name' => $this->name,
                    'cover_pic' => $this->cover_pic,
                    'year' => $this->year,
                    'lang' => $this->lang,
                    'director' => $this->director,
                    'actor' => $this->actor,
                    'status' => $this->status,
                    'content' => $this->content,
                    // 'episode' => MediaEpisodeResource::collection($this->withEpisode)
                ];
            }
        }

        return $return;
    }
}
