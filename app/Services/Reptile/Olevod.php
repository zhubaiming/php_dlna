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

    private function setUrl($arr, $path = '')
    {
        $url = $this->baseUrl . $path;

        foreach ($arr as $k => $v) {
            $url .= "/{$k}/$v";
        }

        $url .= '.html';

        return $url;
    }

    private function getList($id, $year)
    {
        for ($i = 0; $i < 30; $i++) {
            $url = $this->setUrl([
                'id' => $id,
                'page' => $i + 1,
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
                break;
            }

            $list = [];
            foreach ($results as $key => $value) {
//                $list[$i * $results->length + $key]['url'] = $this->baseUrl . trim($value->getAttribute('href'));
//                $list[$i * $results->length + $key]['id'] = trim($value->getAttribute('dids'));
                $list[$key]['url'] = $this->baseUrl . trim($value->getAttribute('href'));
                $list[$key]['id'] = trim($value->getAttribute('dids'));
            }

            sleep(mt_rand(3, 6));

//            var_dump($list);

            $this->getEpisodeList($list);
        }

//        return $list;
    }

    private function getEpisodeList($lists)
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
            $res['cate_id'] = 5;

            var_dump($res['name']);

            $media_id = $this->insertMedia($res);

            Log::info('==================================================');
            Log::info(json_encode($res, 256 + 64 + 32) . "\n");

            $url_list = [];
            for ($i = 1; $i < 101; $i++) {
                $results = $xpath->query('//a[@class="' . $list['id'] . $i . '"]');
                foreach ($results as $result) {
                    $url_list[$i - 1]['episode_name'] = trim($result->nodeValue);
                    $url_list[$i - 1]['url'] = $this->baseUrl . trim($result->getAttribute('href'));
                }
            }
            array_unique($url_list, SORT_REGULAR);

            sleep(mt_rand(3, 6));

            $this->getPlayUrl($url_list, $media_id);
        }

//        var_dump($list['url']);

    }

    private function getPlayUrl($lists, $media_id)
    {
        $res = [];

        foreach ($lists as $key => $list) {
//            var_dump($list['url']);
            $html = $this->getHtmlContent($list['url']);

            $document = new \DOMDocument();
            libxml_use_internal_errors(true);
            $document->loadHTML($html);
            libxml_use_internal_errors(false);
            $xpath = new \DOMXPath($document);

            $results = $xpath->query('//script[@type="text/javascript"]');

            $play_url = null;
            foreach ($results as $result) {
//                if ($str = stristr($result->nodeValue, 'player_aaaa =')) {
//                    $str = mb_substr($str, mb_strlen('player_aaaa ='));
//
//                    $play_url = json_decode($str, true);
//                }

                if ($str = stristr($result->nodeValue, 'player_aaaa=')) {
                    $str = mb_substr($str, mb_strlen('player_aaaa='));

                    $play_url = json_decode($str, true);
                }
            }

//            var_dump($play_url);

            $res[$key]['media_id'] = $media_id;
            $res[$key]['episode_id'] = $this->insertMediaEpisode($list, $media_id);
            $res[$key]['sharpness_id'] = $this->insertMediaSharpness($media_id, '高清');
//            $res[$key]['sharpness_id'] = $this->getMediaSharpness($play_url['url'], $media_id);
            $res[$key]['url'] = $play_url['url'];

            $this->insertMediaUrls($res[$key]);

            Log::info(json_encode($list, 256 + 64 + 32) . "\n");
            Log::info(json_encode($res[$key], 256 + 64 + 32) . "\n");

            sleep(mt_rand(3, 6));
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

    public function beginRep($type, $year)
    {
        $id = '0';
        switch ($type) {
            case 'dy':
                $id = $this->dy();
                break;
            case 'dsj':
                $id = $this->dsj();
                break;
            case 'zy':
                $id = $this->zy();
                break;
        }

        $this->getList($id, $year);

        exit(0);
    }
}
