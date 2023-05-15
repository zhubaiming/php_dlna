<?php

namespace App\Http\Controllers\v1\wechatMini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OpenController extends Controller
{
    public function login(Request $request)
    {
        $code = $request->post('code');

        $url = 'https://api.weixin.qq.com/sns/jscode2session';

        $url = $url . '?appid=wx3c1e42206a0ecabd&secret=92bf68767d76e361e21a698d59f5a76a&js_code=' . $code . '&grant_type=authorization_code';

        $http = Http::get($url);
        $response = $http->json();

        print_r($response);

        print_r($response['openid']);
        print_r($response['session_key']);

//        Log::info(gettype($response->json()) . json_encode($response->json(), 320)); // {"session_key":"R5j3vtEiB5PQf7ljG49IMg==","openid":"oPSoF0YbqNS1ypi6Qaq_70CtOohM"}

        $a = UserInfo::updateOrCreate(
            ['wechat_openid' => $response['openid']],
            ['wechat_session_key' => $response['session_key']]
        );

        dd($a);
    }
}
