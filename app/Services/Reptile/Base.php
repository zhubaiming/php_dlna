<?php

namespace App\Services\Reptile;

use App\Models\Media;
use App\Models\MediaEpisode;
use App\Models\MediaSharpness;
use App\Models\MediaUrl;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Base
{
    protected function getHtmlContent($url)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Accept-Language' => 'zh-CN,zh;q=0.9',
                'Cookie' => '_gid=GA1.2.322045512.1702006738; X_CACHE_KEY=30d781329dc7f8b0d6c951892f279b98; PHPSESSID=dmu4915c911bim4mp62f3d6q2u; 516091=516091; 5160930=5160930; history=%5B%7B%22name%22%3A%22%E4%BC%BC%E7%81%AB%E6%B5%81%E5%B9%B4%22%2C%22pic%22%3A%22https%3A%2F%2Fstatic.olelive.com%2Fupload%2Fvod%2F20231117-1%2F202bbc57e9416e4590533f1153bb2d8c.jpg%22%2C%22link%22%3A%22%2Findex.php%2Fvod%2Fplay%2Fid%2F51609%2Fsid%2F1%2Fnid%2F1.html%22%2C%22part%22%3A%22%E7%AC%AC01%E9%9B%86%22%7D%5D; _ga_BNR15WFH4G=GS1.1.1702020351.2.1.1702020660.0.0.0; _ga=GA1.1.760116643.1702006737',
                'Dnt' => '1',
                'Sec-Ch-Ua' => '"Not_A Brand";v="8", "Chromium";v="120", "Google Chrome";v="120"',
                'Sec-Ch-Ua-Mobile' => '?0',
                'Sec-Ch-Ua-Platform' => '"macOS"',
                'Sec-Fetch-Dest' => 'document',
                'Sec-Fetch-Mode' => 'navigate',
                'Sec-Fetch-Site' => 'same-origin',
                'Sec-Fetch-User' => '?1',
                'Upgrade-Insecure-Requests' => '1',
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            ])->connectTimeout(180)->timeout(60)->retry(3, 180)->get($url);


            //Accept:
            //application/json, text/plain, */*


            //Sec-Fetch-Dest:
            //empty
            //Sec-Fetch-Mode:
            //cors
            //Sec-Fetch-Site:
            //cross-site
            //User-Agent:
            //Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36

            if ($response->successful()) {
                return $response->body();
            } else {
                var_dump($response->body());
            }
        } catch (ConnectionException $e) {
            $this->getHtmlContent($url);
        }
    }

    protected function getJsonContent($url)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json, text/plain, */*',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Accept-Language' => 'zh-CN,zh;q=0.9',
                'Dnt' => '1',
                'Sec-Ch-Ua' => '"Not_A Brand";v="8", "Chromium";v="120", "Google Chrome";v="120"',
                'Sec-Ch-Ua-Mobile' => '?0',
                'Sec-Ch-Ua-Platform' => '"macOS"',
                'Sec-Fetch-Dest' => 'empty',
                'Sec-Fetch-Mode' => 'cors',
                'Sec-Fetch-Site' => 'cross-site',
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            ])->connectTimeout(180)->timeout(60)->retry(3, 180)->get($url);

            if ($response->successful()) {
                $body = $response->body();
                $result = json_decode($body, true);
                return $result['data'];
            } else {
                echo "\033[1;35m获取未成功: 查看replite下的http日志\033[0m" . PHP_EOL;

                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/replite/http.log')
                ])->warning($response->status() . ': ' . $response->body());

                $this->getJsonContent($url);
            }
        } catch (ConnectionException $e) {
            echo "\033[1;31m连接未成功: 查看replite下的http日志\033[0m" . PHP_EOL;

            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/replite/http.log')
            ])->error($e->getCode() . ': ' . $e->getMessage());

            $this->getJsonContent($url);
        }
    }

    protected function insertMedia($data, $count)
    {
        date_default_timezone_set("PRC");//这里是添加的时区函数

        try {
            $media = Media::where(['name' => $data['name']])->firstOrFail();

//            $media->lang = $data['lang'];
//            $media->type_name = $data['type_name'];
            $media->source_id = $data['source_id'];
            $media->updated_at = date('Y-m-d H:i:s');

            if ($data['status'] === $media->status) {
                $media->save();

                if ($count === MediaUrl::where(['media_id' => $media->id])->count()) {
                    return 0;
                } else {
                    return $media->id;
                }
            } else {
                $media->status = $data['status'];

                $media->save();

                return $media->id;
            }
        } catch (ModelNotFoundException $e) {
            $id = DB::table('media')->insertGetId([
                'cate_id' => $data['cate_id'] ?? null,
                'name' => $data['name'] ?? null,
                'name_en' => $data['name_en'] ?? null,
                'cover_pic' => $data['cover_pic'] ?? null,
                'director' => $data['director'] ?? null,
                'actor' => $data['actor'] ?? null,
                'area' => $data['area'] ?? null,
                'year' => $data['year'] ?? null,
                'lang' => $data['lang'] ?? null,
                'class' => $data['class'] ?? null,
                'type_name' => $data['type_name'] ?? null,
                'source' => $data['source'] ?? null,
                'source_id' => $data['source_id'] ?? null,
                'status' => $data['status'] ?? null,
                'blurb' => $data['blurb'] ?? null,
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_flag' => 0,
            ]);

            return $id;
        }
    }

    protected function insertMediaEpisode($data, $media_id)
    {
        try {
            $media_episode = MediaEpisode::where(['media_id' => $media_id, 'episode_name' => $data['episode_name']])->firstOrFail();

            return $media_episode->id;
        } catch (ModelNotFoundException $e) {
            $id = DB::table('media_episode')->insertGetId([
                'media_id' => $media_id,
                'episode_name' => $data['episode_name']
            ]);

            return $id;
        }
    }

    protected function insertMediaSharpness($media_id, $version)
    {
        try {
            $media_sharpness = MediaSharpness::where(['media_id' => $media_id, 'version' => $version])->firstOrFail();

            return $media_sharpness->id;
        } catch (ModelNotFoundException $e) {
            $id = DB::table('media_sharpness')->insertGetId([
                'media_id' => $media_id,
                'version' => $version
            ]);

            return $id;
        }
    }

    protected function insertMediaUrls($data)
    {
        try {
            MediaUrl::where(['media_id' => $data['media_id'], 'episode_id' => $data['episode_id'], 'sharpness_id' => $data['sharpness_id']])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            DB::table('media_urls')->insert($data);
        }
    }
}
