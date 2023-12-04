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
        }

        return response()->json([
            'code' => 200,
            'status' => true,
            'data' => $data,
            'message' => 'ok'
        ]);
    }

    /**
     *
     * public function parameters2xml(Request $request)
     * {
     * $data = $request->post();
     *
     * $result = '<?xml version="1.0" encoding="utf-8" standalone="no"?>' .
     * '<s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">' .
     * '<s:Body>' .
     * '<u:' . $data['action'] . ' xmlns:u="' . $data['serviceType'] . '">';
     * foreach ($data['parameters'] as $key => $value) {
     * $result .= '<' . $key . '>' . $value . '</' . $key . '>';
     * }
     * $result .= '</u:' . $data['action'] . '>' .
     * '</s:Body>' .
     * '</s:Envelope>';
     *
     *
     *
     *
     * <DIDL-Lite xmlns="urn:schemas-upnp-org:metadata-1-0/DIDL-Lite/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sec="http://www.sec.co.kr/" xmlns:upnp="urn:schemas-upnp-org:metadata-1-0/upnp/">'
     * '<item id="0" parentID="-1" restricted="false">'
     * '<upnp:class>object.item.audioItem.musicTrack</upnp:class>'
     * '<dc:title>Some Movie Title</dc:title>'
     * '<dc:creator>John Doe</dc:creator>'
     * '<dc:genre>Rock</dc:genre>'
     * '<dc:abstract>Rock Abstract</dc:abstract>'
     * '<upnp:region>REGION US</upnp:region>'
     * '<res protocolInfo="http-get:*:audio/mpeg:*">http://192.168.1.229:8090/AT1983/LOST%20LOVE%20IN%20THE%20RAIN.mp3</res>'
     * '</item>'
     * '</DIDL-Lite>
     *
     *
     *
     * return new JsonResource(['data' => $result]);
     * }
     */
}
