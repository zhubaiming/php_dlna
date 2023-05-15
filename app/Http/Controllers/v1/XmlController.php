<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class XmlController extends Controller
{
    public function xml2json(Request $request)
    {
        $data = $request->post('xml');

        $data = preg_replace('/<[a-z]+:/', '<', $data);
        $data = preg_replace('/<(\/*)[a-z]+:/', '</', $data);

        if ($xmlArr = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)) {
            $code = 200;
            $msg = 'ok';
            $result = json_decode(json_encode($xmlArr, 320), true);
        } else {
            $code = 0;
            $msg = 'false';
            $result = [];
        }

        return json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $result
        ], 320);
    }

    public
    function parameters2xml(Request $request)
    {
        $data = $request->post();

        $code = 200;
        $msg = 'ok';

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

        return json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $result
        ], 320);
    }

}
