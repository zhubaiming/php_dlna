<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class XmlController extends Controller
{
    public function xml2json(Request $request)
    {
        $data = $request->post('data');

        $xmlparser = xml_parser_create();
        xml_parse_into_struct($xmlparser, $data, $result);
        xml_parser_free($xmlparser);

        dd($result);
    }
}
