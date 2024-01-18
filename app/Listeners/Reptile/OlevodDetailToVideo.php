<?php

namespace App\Listeners\Reptile;

use App\Events\Reptile\Detail;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class OlevodDetailToVideo
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Detail $event): void
    {
        $olevod = DB::table('new_olevod_tv_detail')->find($event->id);

        $video = DB::table('videos')->where(['origin' => '欧乐影院(www.olevod.tv)', 'origin_id' => trim($olevod->id)])->first();

        if (is_null($video)) {
            $cate = DB::table('media_cates')->where(['type' => 'cate', 'name' => trim($olevod->typeId1Name)])->first();
            $type = DB::table('media_cates')->where(['type' => 'type', 'name' => trim($olevod->typeIdName)])->first();
            $area = DB::table('media_cates')->where(['type' => 'area', 'name' => trim($olevod->area)])->first();

            DB::table('videos')->insert([
                'origin' => '欧乐影院(www.olevod.tv)',
                'origin_id' => trim($olevod->id),
                'cate_id' => is_null($cate) ? 0 : $cate->id,
                'cate_name' => trim($olevod->typeId1Name),
                'type_id' => is_null($type) ? 0 : $type->id,
                'type_name' => trim($olevod->typeIdName),
                'area_id' => is_null($area) ? 0 : $area->id,
                'area_name' => trim($olevod->area),
                'name' => trim($olevod->name),
                'name_letter' => strtoupper(trim($olevod->letter)),
                'name_en' => trim($olevod->en),
                'cover_pic' => 'https://static.olelive.com/' . trim($olevod->pic),
                'director' => trim($olevod->director),
                'actor' => trim($olevod->actor),
                'year' => trim($olevod->year),
                'lang' => trim($olevod->lang),
                'status' => trim($olevod->remarks ?? $olevod->version),
                'content' => trim($olevod->content),
                'del_flag' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::createFromTimestamp($olevod->vodTime)
            ]);
        } else {
            DB::table('videos')->where(['id' => $video->id])->update([
                'status' => trim($olevod->remarks ?? $olevod->version),
                'updated_at' => Carbon::createFromTimestamp($olevod->vodTime)
            ]);
        }
    }
}
