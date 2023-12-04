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
                'nameEn' => $this->nameEn, // 英文名称
                'coverPic' => $this->coverPic, // 封面图片
                'area' => $this->area, // 地区
                'year' => $this->year, // 年份
                'lang' => $this->lang, // 语言
                'class' => $this->class, // 剧情
                'typeName' => $this->typeName,
                'source' => $this->source, // 来源
                'version' => $this->version, // 等级
                'status' => $this->status, // 状态
            ];
        } elseif ($request->is('*/show') || $request->is('*/mediaDetail')) {
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

//        return [
//            'id' => $this->id,
//            'name' => $this->name, // 名称
//            'nameEn' => $this->nameEn, // 英文名称
//            'coverPic' => $this->coverPic, // 封面图片
//            'area' => $this->area, // 地区
//            'year' => $this->year, // 年份
//            'lang' => $this->lang, // 语言
//            'class' => $this->class, // 剧情
//            'typeName' => $this->typeName,
//            'source' => $this->source, // 来源
//            'version' => $this->version, // 等级
//            'status' => $this->status, // 状态
//            'director' => $this->director,
//            'actor' => $this->actor,
//            'blurb' => $this->blurb,
//            'urls' => $this->urls
//        ];
    }
}
