<?php

namespace App\Http\Controllers\Dlna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActionsController extends Controller
{
    protected $xml;

    public function __construct()
    {
        $this->xml = '<?xml version="1.0" encoding="utf-8" standalone="no"?>';

        $this->xml .= '<s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">';

        $this->xml .= '<s:Body>';
    }

    private function setEnd()
    {
        $this->xml .= '</s:Body>';

        $this->xml .= '</s:Envelope>';
    }

    public function setAVTransportControlBody(Request $request)
    {
        $this->xml .= '<u:SetAVTransportURI xmlns:u="urn:schemas-upnp-org:service:AVTransport:1">';

        $this->xml .= '<InstanceID>' . $request->post('id') . '</InstanceID>';
        $this->xml .= '<CurrentURI>' . $request->post('url') . '</CurrentURI>';
        $this->xml .= '<CurrentURIMetaData>';

        $metaData = $request->post('metaData');

        $currentUriMetaDataXml = '<DIDL-Lite xmlns="urn:schemas-upnp-org:metadata-1-0/DIDL-Lite/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:netease="http://music.163.com/dlna/" xmlns:upnp="urn:schemas-upnp-org:metadata-1-0/upnp/">';
        $currentUriMetaDataXml .= '<item id="';
        $currentUriMetaDataXml .= $request->post('id');
        $currentUriMetaDataXml .= '" parentID="';
        $currentUriMetaDataXml .= $request->post('id') - 1;
        $currentUriMetaDataXml .= '" restricted="">';
        $currentUriMetaDataXml .= '<dc:title>' . $metaData['title'] . '</dc:title>';
        $currentUriMetaDataXml .= '<dc:creator>' . $metaData['creator'] . '</dc:creator>';
        $currentUriMetaDataXml .= '<dc:date>' . $metaData['date'] . '</dc:date>';
        $currentUriMetaDataXml .= '<dc:description>' . $metaData['description'] . '</dc:description>';
        $currentUriMetaDataXml .= '<upnp:album>' . $metaData['album'] . '</upnp:album>';
        $currentUriMetaDataXml .= '<upnp:albumArtURI>' . $metaData['albumArtURI'] . '</upnp:albumArtURI>';
        $currentUriMetaDataXml .= '<upnp:artist>' . $metaData['artist'] . '</upnp:artist>';
        $currentUriMetaDataXml .= '<upnp:class>object.item.videoItem</upnp:class>';
        $currentUriMetaDataXml .= '<res duration="" protocolInfo="http-get:*:video/mp4:DLNA.ORG_OP=01">';
        $currentUriMetaDataXml .= $request->post('url');
        $currentUriMetaDataXml .= '</res></item></DIDL-Lite>';

        $this->xml .= htmlentities($currentUriMetaDataXml, ENT_QUOTES | ENT_IGNORE, 'UTF-8');

        $this->xml .= '</CurrentURIMetaData>';

        $this->xml .= '</u:SetAVTransportURI>';

        $this->setEnd();

        return response()->json([
            'code' => 200,
            'message' => 'ok',
            'data' => $this->xml
        ]);
    }
}
