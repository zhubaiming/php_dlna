<?php

namespace App\Http\Resources\wechatMiniApp;

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
        $res = [];

        if ($request->is('wechatMiniApp/*')) {
            $res = match ($request->input('func')) {
                "index" => [
                    'id' => $this->id,
                    'cover_pic' => $this->cover_pic,
                    'origin' => $this->origin,
                    'name' => $this->name,
                    'area' => $this->area_name,
                    'lang' => $this->lang,
                    'year' => $this->year,
                    'type' => $this->type_name,
                    'status' => $this->remarks
                ]
            };
        }

        return $res;

//        $arr = [];
//        if ($request->is('*/index') || $request->is('*/mediaList')) {
//            $arr = [
//                'id' => $this->id,
//                'origin' => $this->origin,
//                'typeName' => $this->type_name,
//                'area' => $this->area_name,
//                'name' => $this->name, // 名称
//                'coverPic' => $this->cover_pic, // 封面图片
//                'year' => $this->year, // 年份
//                'lang' => $this->lang, // 语言
//                'status' => $this->status, // 状态
//            ];
//        } elseif ($request->is('*/show') || $request->is('*/mediaDetail')) {
//            $arr = [
//                'id' => $this->id, //
//                'name' => $this->name, //
//                'coverPic' => $this->cover_pic, //
//                'origin' => $this->origin, //
//                'typeName' => $this->type_name, //
//                'area' => $this->area_name, //
//                'director' => $this->director,
//                'actor' => $this->actor, //
//                'year' => $this->year, //
//                'lang' => $this->lang, //
//                'status' => $this->status, //
//                'content' => $this->content, //
//                'episode' => MediaEpisodeResource::collection($this->withEpisode) //
//            ];
//        }
//
//        return $arr;
    }
}
