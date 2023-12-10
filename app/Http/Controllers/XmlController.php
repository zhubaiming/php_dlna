<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class XmlController extends Controller
{
    public function xml2json(Request $request)
    {
        $xml = $request->post('xml');

        $xml = preg_replace('/<[a-z]+:/', '<', $xml);
        $xml = preg_replace('/<(\/*)[a-z]+:/', '</', $xml);

        $data = [];
        if ($xmlArr = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)) {
            preg_match('/http(s)?:\/\/([0-9]{1,3}\.?){1,4}(:[0-9]*)?/', $request->post('location'), $matches);

            $data = json_decode(json_encode($xmlArr, 256 + 64 + 32), true);

            $data['URLBase'] = $data['URLBase'] ?? $matches[0];

            if (str_ends_with($data['URLBase'], '/')) $data['URLBase'] = mb_substr($data['URLBase'], 0, -1);

            $data['device']['serviceList']['service'] = $this->joinLeftSlashToBegin($data['device']['serviceList']['service']);

            $data['device']['icon'] = array_key_exists('device', $data) && array_key_exists('iconList', $data['device']) ? $data['URLBase'] . $this->getDeviceMaxDepthIconUrl($data['device']['iconList']['icon']) : '';
        }

        return response()->json([
            'code' => 200,
            'message' => 'ok',
            'data' => $data
        ]);
    }

    private function getDeviceMaxDepthIconUrl($list)
    {
        $depth = 0;
        $width = 0;
        $k = -1;
        foreach ($list as $key => $val) {
            if ($width <= $val['width'] && $depth <= $val['depth']) {
                $depth = $val['depth'];
                $width = $val['width'];
                $k = $key;
            }
        }

        return $list[$k];
    }

    private function joinLeftSlashToBegin($service)
    {
        foreach ($service as $key => $value) {
            foreach ($value as $k => $v) {
                if (strpos(strtolower($k), 'url') && !str_starts_with($v, '/')) {
                    $service[$key][$k] = '/' . $v;
                }
            }
        }

        return $service;
    }
}
