<?php

namespace App\Services\Reptile;

use Illuminate\Support\Facades\Log;

class Olevod extends Base
{
    private $baseUrl = 'https://www.olevod.com';

    private function dy()
    {
        return '1';
    }

    private function dsj()
    {
        return '2';
    }

    private function zy()
    {
        return '3';
    }

    private function getTypeNum($id, $type_name)
    {
        if ('1' === $id) {
            switch ($type_name) {
                case '动作片':
                    $id = 101;
                    break;
                case '喜剧片':
                    $id = 102;
                    break;
                case '爱情片':
                    $id = 103;
                    break;
                case '科幻片':
                    $id = 104;
                    break;
                case '恐怖片':
                    $id = 105;
                    break;
                case '剧情片':
                    $id = 106;
                    break;
                case '战争片':
                    $id = 107;
                    break;
                case '动画片':
                    $id = 108;
                    break;
                case '悬疑片':
                    $id = 109;
                    break;
                case '惊悚片':
                    $id = 110;
                    break;
                case '纪录片':
                    $id = 111;
                    break;
                case '奇幻片':
                    $id = 112;
                    break;
                case '犯罪片':
                    $id = 113;
                    break;
                default:
                    break;
            }
        } elseif ('2' === $id) {
            switch ($type_name) {
                case '国产剧':
                    $id = 202;
                    break;
                case '欧美剧':
                    $id = 201;
                    break;
                case '港台剧':
                    $id = 203;
                    break;
                case '日韩剧':
                    $id = 204;
                    break;
                default:
                    break;
            }
        } elseif ('3' === $id) {
            switch ($type_name) {
                case '真人秀':
                    $id = 305;
                    break;
                case '音乐':
                    $id = 302;
                    break;
                case '搞笑':
                    $id = 304;
                    break;
                case '家庭':
                    $id = 301;
                    break;
                case '曲艺':
                    $id = 303;
                    break;
                default:
                    break;
            }
        }

        return $id;
    }

    private function setUrl($arr, $path = '')
    {
        $url = $this->baseUrl . $path;

        foreach ($arr as $k => $v) {
            $url .= "/{$k}/$v";
        }

        $url .= '.html';

        return $url;
    }

//    private function getList($id, $year, $lang, $type_name, $cate_id, $endPage)
    private function getList($id, $year, $cate_id)
    {
        for ($i = 30; $i > 0; $i--) {
            $url = $this->setUrl([
                'id' => $id,
//                'lang' => $lang,
                'page' => $i,
                'year' => $year
            ], '/index.php/vod/show');

            var_dump($url);
            $html = $this->getHtmlContent($url);

            $document = new \DOMDocument();
            libxml_use_internal_errors(true);
            $document->loadHTML($html);
            libxml_use_internal_errors(false);
            $xpath = new \DOMXPath($document);

            $results = $xpath->query('//a[@class="vodlist_thumb lazyload"]');

            if (0 === $results->length) {
                sleep(mt_rand(2, 4));
                continue;
            }

            $list = [];
            foreach ($results as $key => $value) {
                $list[$key]['url'] = $this->baseUrl . trim($value->getAttribute('href'));
                $list[$key]['id'] = trim($value->getAttribute('dids'));
            }

            sleep(mt_rand(2, 4));

//            $this->getEpisodeList(array_reverse($list), $lang, $type_name, $cate_id);
            $this->getEpisodeList(array_reverse($list), $cate_id);
        }
    }

//    private function getEpisodeList($lists, $lang, $type_name, $cate_id)
    private function getEpisodeList($lists, $cate_id)
    {
        foreach ($lists as $list) {
            $res = [];

            $html = $this->getHtmlContent($list['url']);

            $document = new \DOMDocument();
            libxml_use_internal_errors(true);
            $document->loadHTML($html);
            libxml_use_internal_errors(false);
            $xpath = new \DOMXPath($document);

            $results = $xpath->query('//a[@class="vodlist_thumb lazyload"]');
            $res['cover_pic'] = trim($results[0]->getAttribute('data-original'));

            $results = $xpath->query('//h2[@class="title scookie"]');
            $res['name'] = trim($results[0]->childNodes[$results[0]->childNodes->length - 1]->nodeValue);

            $results = $xpath->query('//span[@class="text_muted hidden_xs"]');
            $res['year'] = trim($results[1]->nextSibling->nodeValue);
            $res['area'] = trim($results[2]->nextSibling->nodeValue);
            $res['class'] = trim($results[3]->nextSibling->nodeValue);

//            $res['lang'] = $lang;
//            $res['type_name'] = $type_name;

            $res['status'] = $xpath->query('//span[@class="data_style"]')[0]->nodeValue;

            $results = $xpath->query('//li[@class="data"]');
            $actorList = [];
            foreach ($results[2]->childNodes as $v) {
                if (property_exists($v, 'tagName') && 'a' === $v->tagName) {
                    if ('' != trim($v->nodeValue)) {
                        array_push($actorList, trim($v->nodeValue));
                    }
                }
            }
            $res['actor'] = implode('/', $actorList);

            foreach ($results[3]->childNodes as $v) {
                if (property_exists($v, 'tagName') && 'a' === $v->tagName) {
                    $res['director'] = trim($v->nodeValue);
                }
            }

            $results = $xpath->query('//div[@class="content_desc context clearfix"]');
            foreach ($results as $result) {
                foreach ($result->childNodes as $v) {
                    if (property_exists($v, 'tagName') && 'span' === $v->tagName) {
                        $blurb = trim($v->nodeValue);
                        $blurb = preg_replace("/\s| |　/", "", $blurb);
                        $blurb = preg_replace("/\'/", "`", $blurb);
                        $res['blurb'] = $blurb;
                    }
                }
            }

            $res['source'] = '欧乐影院';
            $res['cate_id'] = $cate_id;
            $res['source_id'] = $list['id'];

            echo $res['name'];


            Log::info('==================================================');
            Log::info(json_encode($res, 256 + 64 + 32) . "\n");

            $url_list = [];
            for ($i = 1; $i < 101; $i++) {
                $results = $xpath->query('//a[@class="' . $list['id'] . $i . '"]');
                if (0 === $results->length) {
                    break;
                }
                foreach ($results as $result) {
                    $url_list[$i - 1]['episode_name'] = trim($result->nodeValue);
                    $url_list[$i - 1]['url'] = $this->baseUrl . trim($result->getAttribute('href'));
                }
            }
            array_unique($url_list, SORT_REGULAR);

            sleep(mt_rand(2, 4));

            $media_id = $this->insertMedia($res, count($url_list));

            if (0 === $media_id) {
                echo ' - 无需更新' . PHP_EOL;
                continue;
            }
            echo PHP_EOL;

            $this->getPlayUrl($url_list, $media_id);
        }
    }

    private function getPlayUrl($lists, $media_id)
    {
        $res = [];

        foreach ($lists as $key => $list) {
            $html = $this->getHtmlContent($list['url']);

            if (0 === mb_strlen($html)) $this->getPlayUrl($lists, $media_id);

            $document = new \DOMDocument();
            libxml_use_internal_errors(true);
            $document->loadHTML($html);
            libxml_use_internal_errors(false);
            $xpath = new \DOMXPath($document);

            $results = $xpath->query('//script[@type="text/javascript"]');

            $play_url = null;
            foreach ($results as $result) {
                if ($str = stristr($result->nodeValue, 'player_aaaa=')) {
                    $str = mb_substr($str, mb_strlen('player_aaaa='));

                    $play_url = json_decode($str, true);
                }
            }

            $res[$key]['media_id'] = $media_id;
            $res[$key]['episode_id'] = $this->insertMediaEpisode($list, $media_id);
            $res[$key]['sharpness_id'] = $this->insertMediaSharpness($media_id, '高清');
            $res[$key]['url'] = $play_url['url'];

            $this->insertMediaUrls($res[$key]);

            Log::info(json_encode($list, 256 + 64 + 32) . "\n");
            Log::info(json_encode($res[$key], 256 + 64 + 32) . "\n");

            sleep(mt_rand(2, 4));
        }
    }

    private function getMediaSharpness($m3u8_url, $media_id)
    {
//        $html = $this->getHtmlContent($m3u8_url);
//
//        $str = mb_substr($html, stripos($html, 'RESOLUTION=') + 11);
//        $str = mb_substr($str, 0, stripos($str, ','));

        return $this->insertMediaSharpness($media_id, '高清');
    }

//    public function beginRep($type, $year, $type_name, $lang, $endPage)
    public function beginRep($type, $year)
    {
        $id = '0';
        $cate_id = 0;
        switch ($type) {
            case 'dy':
                $id = $this->dy();
                $cate_id = 4;
                break;
            case 'dsj':
                $id = $this->dsj();
                $cate_id = 5;
                break;
            case 'zy':
                $id = $this->zy();
                $cate_id = 6;
                break;
        }

//        $id = $this->getTypeNum($id, $type_name);
//
//        $this->getList($id, $year, $lang, $type_name, $cate_id, $endPage);
        $this->getList($id, $year, $cate_id);

        exit(0);
    }
}
