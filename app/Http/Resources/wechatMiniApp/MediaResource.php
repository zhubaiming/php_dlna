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
//        return parent::toArray($request);
        $arr = [];
        if ($request->is('*/index') || $request->is('*/mediaList')) {
            $arr = [
                'id' => $this->id,
                'name' => $this->name, // 名称
                'nameEn' => $this->name_en, // 英文名称
                'coverPic' => $this->cover_pic, // 封面图片
                'area' => $this->area, // 地区
                'year' => $this->year, // 年份
                'lang' => $this->lang, // 语言
                'class' => $this->class, // 剧情
                'typeName' => $this->type_name,
                'source' => $this->source, // 来源
//                'version' => $this->version, // 等级
                'status' => $this->status, // 状态
            ];
        } elseif ($request->is('*/show') || $request->is('*/mediaDetail')) {
            $arr = [
                'id' => $this->id, //
                'name' => $this->name, //
                'nameEn' => $this->name_en, // 英文名称
                'coverPic' => $this->cover_pic, //
                'area' => $this->area, //
                'year' => $this->year, //
                'lang' => $this->lang, //
                'class' => $this->class, // 剧情
                'typeName' => $this->type_name, //
                'source' => $this->source, //
                'status' => $this->status, //
                'director' => $this->director,
                'actor' => $this->actor, //
                'blurb' => $this->blurb, //
                'episode' => MediaEpisodeResource::collection($this->withEpisode) //
            ];
        }

        return $arr;
    }
}
