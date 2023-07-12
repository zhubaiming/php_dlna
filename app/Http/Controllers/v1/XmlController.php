<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\wechatMini\JsonResource;
use Illuminate\Http\Request;

class XmlController extends Controller
{
    public function xml2json(Request $request)
    {
        $data = $request->post('xml');

        $data = preg_replace('/<[a-z]+:/', '<', $data);
        $data = preg_replace('/<(\/*)[a-z]+:/', '</', $data);

        if ($xmlArr = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)) {
            $result = json_decode(json_encode($xmlArr, 320), true);
        } else {
            $result = [];
        }

        return new JsonResource($result);
    }

    public
    function parameters2xml(Request $request)
    {
        $data = $request->post();

        $result = '<?xml version="1.0" encoding="utf-8" standalone="no"?>' .
            '<s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">' .
            '<s:Body>' .
            '<u:' . $data['action'] . ' xmlns:u="' . $data['serviceType'] . '">';
        foreach ($data['parameters'] as $key => $value) {
            $result .= '<' . $key . '>' . $value . '</' . $key . '>';
        }
        $result .= '</u:' . $data['action'] . '>' .
            '</s:Body>' .
            '</s:Envelope>';

        return new JsonResource(['data' => $result]);
    }

}
