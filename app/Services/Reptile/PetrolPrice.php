<?php

namespace App\Services\Reptile;

use Illuminate\Support\Facades\DB;

class PetrolPrice extends Base
{
    public function getPrice()
    {
        $url = 'https://www.xiaoxiongyouhao.com/fprice/';

        $html = $this->getHtmlContent($url);

        $document = new \DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML($html);
        libxml_use_internal_errors(false);
        $xpath = new \DOMXPath($document);

        $results = $xpath->query('//table[@class="table"]');

        $province_arr = [];
        $i = 0;

        foreach ($results as $result) {
            foreach ($result->childNodes as $v1) {
                if (property_exists($v1, 'tagName') && 'tbody' === $v1->tagName) {
                    foreach ($v1->childNodes as $v2) {
                        if (property_exists($v2, 'tagName') && 'tr' === $v2->tagName) {
                            foreach ($v2->childNodes as $v3) {
                                if (property_exists($v3, 'tagName') && 'td' === $v3->tagName) {
                                    foreach ($v3->childNodes as $v4) {
                                        if (property_exists($v4, 'tagName') && 'a' === $v4->tagName) {
                                            $province_arr[$i]['province'] = trim($v4->nodeValue);
                                            $province_arr[$i]['url'] = $v4->getAttribute('href');
                                        }
                                    }
                                }
                            }
                        }

                        $i++;
                    }
                }
            }
        }

        foreach ($province_arr as $province) {
            $url = 'https://www.xiaoxiongyouhao.com' . $province['url'];
            $province = $province['province'];

            var_dump($url);
            var_dump($province);

            $html = $this->getHtmlContent($url);

            $document = new \DOMDocument();
            libxml_use_internal_errors(true);
            $document->loadHTML($html);
            libxml_use_internal_errors(false);
            $xpath = new \DOMXPath($document);

            $results = $xpath->query('//tr');

            $j = 0;
            $ths = [];
            foreach ($results as $key => $result) {
                if ($key < 2) {
                    continue;
                }

                $city = '';
                foreach ($result->childNodes as $k1 => $v1) { //th
                    foreach ($v1->childNodes as $v2) {
                        if (property_exists($v2, 'tagName') && ('a' === $v2->tagName || 'div' === $v2->tagName)) {
                            if ($k1 == 1) {
                                $city = trim($v2->nodeValue);
                            }

                            if ($k1 == 3) {
                                $ths[$j]['province'] = $province;
                                $ths[$j]['city'] = $city;
                                $ths[$j]['petrol_num'] = '92';
                                $ths[$j]['price'] = trim($v2->nodeValue);
                                $ths[$j]['updated_at'] = date('Y-m-d H:i:s');
                            }

                            if ($k1 == 5) {
                                $ths[$j]['province'] = $province;
                                $ths[$j]['city'] = $city;
                                $ths[$j]['petrol_num'] = '95';
                                $ths[$j]['price'] = trim($v2->nodeValue);
                                $ths[$j]['updated_at'] = date('Y-m-d H:i:s');
                            }

                            $j++;
                        }
                    }
                }
            }

            DB::table('petrol_price')->insert($ths);
        }
    }
}
