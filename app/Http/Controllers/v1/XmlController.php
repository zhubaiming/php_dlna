<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class XmlController extends Controller
{
    public function xml2json(Request $request)
    {
        $data = $request->post('xml');

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

    public function json2xml(Request $request)
    {
        $data = $request->post('data');

        $result = '<\?xml version=\"1.0\"\?>';
    }
}
