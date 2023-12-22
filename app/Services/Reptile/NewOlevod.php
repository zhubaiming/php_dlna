<?php

namespace App\Services\Reptile;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewOlevod extends Base
{
    private $base_api_url = 'https://api.olelive.com/v1/pub/vod';

    private $base_source_url = 'https://static.olelive.com/';

    private $version_level = [
        '高清' => 1,
        '超清' => 2
    ];

    public function beginRep()
    {
//        dd($this->getVV());

//        $this->getList();

//        sleep(mt_rand(5, 8));

        $this->getDetail();

        exit('全部结束' . PHP_EOL);
    }

    private function getList()
    {
        $total = 0;

        $page_size = 20;

        // 分类：1-电影，2-电视剧，3-综艺，4-动漫，6-vip蓝光
        $cates = [1, 2, 3, 4, 6];
        foreach ($cates as $cate) {
            $url = $this->base_api_url . '/list/true/3/0/0/' . $cate . '/0/0/update/1000/48?_vv=' . $this->getVV();
            $res = $this->getJsonContent($url);

            $page_total = (int)ceil($res['total'] / $page_size) + 1;

            echo "\033[1;35m" . "分类: " . $cate . ", 共 " . $page_total . " 页\033[0m" . PHP_EOL;

            for ($i = 1; $i < $page_total; $i++) {
                $url = $this->base_api_url . '/list/true/3/0/0/' . $cate . '/0/0/update/' . $i . '/' . $page_size . '?_vv=' . $this->getVV();

                echo "\033[1;32m" . "第" . $i . "页" . "\033[0m" . PHP_EOL;

                $res = $this->getJsonContent($url);

                foreach ($res['list'] as $detail) {
                    $this->insertToReptile($detail);
                    $total++;
                }

                sleep(mt_rand(2, 4));
            }

            sleep(mt_rand(2, 4));
        }

        echo "\033[0;31m共 " . $total . " 条\033[0m" . PHP_EOL;
    }

    private function insertToReptile($data)
    {
        $db = DB::table('new_olevod')->where(['id' => $data['id']])->first();
        if (null === $db) {
            $data['is_update'] = false;
            DB::table('new_olevod')->insert($data);
        } else {
            if ($db->remarks !== $data['remarks']) {
                DB::table('new_olevod')->where(['id' => $data['id']])->update([
                    'remarks' => $data['remarks'],
                    'is_update' => false
                ]);
            }
        }

        $str = $data['id'] . '-' . $data['name'];
        Log::channel('replite')->info($str);
        echo "\033[0;36m" . $str . "\033[0m" . PHP_EOL;
    }

    private function getDetail()
    {
        DB::table('new_olevod')->where(['vip' => false, 'is_update' => false])->orderBy('id', 'desc')->lazyById()->each(function (object $video) {

            echo "\033[0;32m即将获取" . $video->name . "\033[0m" . PHP_EOL;

            $url = $this->base_api_url . '/detail/' . $video->id . '/true?_vv=' . $this->getVV();

            echo $url . PHP_EOL;

            $res = $this->getJsonContent($url);

            $urls = [];

            if (!array_key_exists('urls', $res) || !is_array($res['urls'])) {
                $res['urls'] = [['url' => '']];
            }

            foreach ($res['urls'] as $url) {
                if (strlen($url['url']) === 0) {
                    $urls = [];
                    Log::channel('replite')->info($video->id . '-' . $video->name);
                    break;
                }

                $urls[] = $url;
            }

            if (count($urls) !== 0) {
                $media_id = $this->insertToMedia($res);

                // 清晰度
                $sharpness_id = $this->insertToMediaSharpness($media_id, $video->version);

                $this->insertToMediaEpisode($media_id, $sharpness_id, $urls);

                DB::table('new_olevod')->where(['id' => $video->id])->update(['is_update' => true]);

                echo "\033[0;36m" . $video->name . "\033[0m" . PHP_EOL;
            }

            sleep(mt_rand(2, 4));
        });
    }

    private function insertToMedia($data)
    {
        $media = DB::table('media')->where(['origin' => '欧乐影院', 'type_name' => trim($data['typeIdName']), 'year' => trim($data['year']), 'name' => trim($data['name'])])->first();

        if (null === $media) {
            $media_id = DB::table('media')->insertGetId([
                'origin' => '欧乐影院',
                'cate_name' => trim($data['typeId1Name']),
                'type_name' => trim($data['typeIdName']),
                'area_name' => trim($data['area']),
                'name' => trim($data['name']),
                'name_letter' => strtoupper(trim($data['letter'])),
                'name_en' => trim($data['en']),
                'cover_pic' => $this->base_source_url . trim($data['pic']),
                'director' => trim($data['director']),
                'actor' => trim($data['actor']),
                'year' => trim($data['year']),
                'lang' => trim($data['lang']),
                'remarks' => trim($data['remarks']),
                'version' => trim($data['version']),
                'content' => trim($data['content']),
                'del_flag' => 0,
                'created_at' => date('Y-m-d H:i:s', trim($data['timeAdd'])),
                'updated_at' => date('Y-m-d H:i:s', trim($data['vodTime']))
            ]);
        } else {
            DB::table('media')->where('id', $media->id)->update([
                'updated_at' => date('Y-m-d H:i:s', trim($data['vodTime'])),
                'remarks' => trim($data['remarks']),
                'version' => mb_strlen(trim($data['version'])) !== 0 && trim($data['version']) !== $media->version ? $media->version . '/' . trim($data['version']) : $media->version,
            ]);

            $media_id = $media->id;
        }

        return $media_id;
    }

    private function insertToMediaSharpness($media_id, $version)
    {
        $media_sharpness = DB::table('media_sharpness')->where(['media_id' => $media_id, 'version' => $version])->first();
        if (null === $media_sharpness) {
            return DB::table('media_sharpness')->insertGetId([
                'media_id' => $media_id,
                'version' => $version
            ]);
        } else {
            return $media_sharpness->id;
        }
    }

    private function insertToMediaEpisode($media_id, $sharpness_id, $urls)
    {
        foreach ($urls as $url) {
            $media_episode = DB::table('media_episode')->where(['media_id' => $media_id, 'episode_name' => trim($url['title'])])->first();
            if (null === $media_episode) {
                $episode_id = DB::table('media_episode')->insertGetId([
                    'media_id' => $media_id,
                    'episode_name' => trim($url['title'])
                ]);

                DB::table('media_urls')->insert([
                    'media_id' => $media_id,
                    'episode_id' => $episode_id,
                    'sharpness_id' => $sharpness_id,
                    'url' => trim($url['url'])
                ]);
            }
        }
    }


    private function insertToDb($data)
    {
        echo $data['year'] . ' - ' . $data['name'] . ' - ' . $data['typeIdName'] . ' - ' . $data['area'];

        $media_id = $this->insertToMedia($data);

        if (0 != $media_id) {
            $sharpness_id = $this->insertToMediaSharpness($data, $media_id);

            $this->insertToMediaEpisode($data['urls'], $media_id, $sharpness_id);
        }
    }

//    private function insertToMedia($data)
//    {
//        echo $data['name'] . ' - ' . $data['remarks'] . ' - ' . $data['typeIdName'] . ' - ' . $data['area'];
//
//        $media = DB::table('media_copy2')->where(['origin_id' => trim($data['id']), 'origin' => '欧乐影院'])->value('id');
//        if (null == $media) {
//            $cate = DB::table('sources_cate')->where(['name' => trim($data['typeIdName'])])->first();
//            $area = DB::table('sources_area')->where('name', 'like', '%' . trim($data['area']) . '%')->first();
//
//            echo ' - 新增' . PHP_EOL;
//
//            return DB::table('media_copy2')->insertGetId([
//                'origin' => '欧乐影院',
//                'origin_id' => trim($data['id']),
//                'cate_id' => $cate?->pid,
//                'cate_name' => DB::table('sources_cate')->where(['id' => $cate->pid])->value('name') ?? null,
//                'type_id' => $cate?->id,
//                'type_name' => null == $cate ? trim($data['typeIdName']) : $cate->name,
//                'area_id' => $area?->id,
//                'area_name' => null == $area ? trim($data['area']) : $area->name,
//                'name' => trim($data['name']),
//                'name_en' => trim($data['en']),
//                'cover_pic' => $this->base_source_url . trim($data['pic']),
//                'director' => trim($data['director']),
//                'actor' => trim($data['actor']),
//                'year' => trim($data['year']),
//                'lang' => trim($data['lang']),
//                'status' => trim($data['remarks']),
//                'content' => trim($data['content']),
//                'del_flag' => 0,
//                'created_at' => date('Y-m-d H:i:s', trim($data['timeAdd'])),
//                'updated_at' => date('Y-m-d H:i:s', trim($data['vodTime']))
//            ]);
//        } else {
//            $media->status = trim($data['remarks']);
//            $media->updated_at = date('Y-m-d H:i:s', trim($data['vodTime']));
//
//            if ($media->isDirty()) {
//                $media->save();
//
//                echo ' - 更新 - ' . trim($data['remarks']) . PHP_EOL;
//
//                return $media->id;
//            } else {
//                echo ' - 无需更新' . PHP_EOL;
//
//                return 0;
//            }
//        }
//    }


    private function insertToMediaUrl($url, $media_id, $sharpness_id, $episode_id)
    {
        DB::table('media_urls_copy3')->insert([
            'media_id' => $media_id,
            'episode_id' => $sharpness_id,
            'sharpness_id' => $episode_id,
            'url' => trim($url)
        ]);
    }

    private function getVV()
    {
        $time = (string)time();

        $r = ['', '', '', ''];
        for ($i = 0; $i < strlen($time); $i++) {
            $e = $this->ce($time[$i]);

            $r[0] .= substr($e, 2, 1);
            $r[1] .= substr($e, 3, 1);
            $r[2] .= substr($e, 4, 1);
            $r[3] .= substr($e, 5);
        }

        $a = [];
        for ($i = 0; $i < count($r); $i++) {
            $e = dechex(bindec($r[$i]));

            switch (strlen($e)) {
                case 0:
                    $e = '000';
                    break;
                case 1:
                    $e = '00' . $e;
                    break;
                case 2:
                    $e = '0' . $e;
                    break;
            }

            $a[$i] = $e;
        }

        $n = md5($time);

        $res = substr($n, 0, 3) . $a[0] . substr($n, 6, 5) . $a[1] . substr($n, 14, 5) . $a[2] . substr($n, 22, 5) . $a[3] . substr($n, 30);

        return $res;
    }

    private function ce($e)
    {
        $t = [];
        $r = explode(" ", $e);

        for ($i = 0; $i < count($r); $i++) {
            if (0 != $i) $t[] = " ";

            $x = base_convert(bin2hex($r[$i]), 16, 2);

            $t[] = $x;
        }

        return implode("", $t);
    }
}
