<?php

namespace App\Services\Reptile;

use App\Services\HttpTrait;
use Illuminate\Support\Facades\DB;

class NewOlevod
{
    use HttpTrait;

    private string $base_api_url = 'https://api.olelive.com/v1/pub/vod';

    private string $base_source_url = 'https://static.olelive.com/';

    private array $header = [
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
    ];

    public function getVV()
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

    private function getList()
    {
        echo "\033[0;32m开始获取列表数据\033[0m" . PHP_EOL;

        $total = 0;
        $page_size = 48;

        // 分类：1-电影，2-电视剧，3-综艺，4-动漫，6-vip蓝光
        $cates = [1 => '电影', 2 => '电视剧', 3 => '综艺', 4 => '动漫', 6 => 'vip蓝光'];
        foreach ($cates as $cate => $val) {
            sleep(mt_rand(2, 4));

            $response = $this->setHeaders($this->header)->getHttp($this->base_api_url . '/list/true/3/0/0/' . $cate . '/0/0/update/1000/48', ['_vv' => $this->getVV()]);
            $page_total = intval(ceil($response['data']['total'] / $page_size) + 1);

            echo "\033[36m当前获取 " . $val . " 列表，共 " . ($page_total - 1) . " 页\033[0m" . PHP_EOL;

            for ($i = 1; $i < $page_total; $i++) {
                sleep(mt_rand(2, 4));

                $response = $this->setHeaders($this->header)->getHttp($this->base_api_url . '/list/true/3/0/0/' . $cate . '/0/0/update/' . $i . '/' . $page_size, ['_vv' => $this->getVV()]);

                foreach ($response['data']['list'] as $detail) {
                    DB::table('new_olevod_tv')->updateOrInsert(['id' => $detail['id']], $detail);
                    $total++;
                }
            }
        }

        echo "\033[1;32m列表数据获取完成，共 " . $total . " 条\033[0m" . PHP_EOL;
    }

    private function getDetail()
    {
        echo "\033[0;32m开始获取详情数据\033[0m" . PHP_EOL;

        DB::table('new_olevod_tv')->orderBy('id', 'asc')->lazy()->each(function (object $tv) {
            sleep(mt_rand(2, 4));

            $response = $this->setHeaders($this->header)->getHttp($this->base_api_url . '/detail/' . $tv->id . '/true', ['_vv' => $this->getVV()]);

            echo "\033[36m当前获取 " . $response['data']['name'] . "\033[0m" . PHP_EOL;

            $data = $response['data'];
            $data['urls'] = json_encode($data['urls'], JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES + JSON_NUMERIC_CHECK);

            DB::table('new_olevod_tv_detail')->updateOrInsert(['id' => $data['id']], $data);
        });
    }

    public function beginRep()
    {
//        $this->getList();

        echo "========== ********** ========== ********** ==========" . PHP_EOL;

        $this->getDetail();
    }
}
