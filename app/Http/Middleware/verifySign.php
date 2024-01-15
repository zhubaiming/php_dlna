<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class verifySign
{
    /**
     * iv 偏移量
     * key 密钥
     * sign 签名
     * time-stamp 时间戳
     * nonce-str 随机字符串
     */
    private array $header_keys = [
        'iv',
        'key',
        'time-stamp',
        'nonce-str',
        'sign'
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        foreach ($this->header_keys as $name) {
            if (!$request->hasHeader($name)) {
                //            throw new BusinessException(3002, '登录失败');
                dd('登录失败');
            }

            $name = str_replace('-', '_', $name);

            $$name = $request->header($name);
        }

        $time = Carbon::now();

        if ('local' !== config('app.env')) {
            if ($time->lt(Carbon::createFromTimestamp($time_stamp)) || $time->addSeconds(120)->lt(Carbon::createFromTimestamp($time_stamp))) {
                //            throw new BusinessException(3004, '密钥已过期');
                dd(Carbon::createFromTimestamp($time_stamp), $time, $time->addSeconds(120), '密钥已过期');
            }
        }

        $all = $request->input();
        if ($request->isMethod('get') || $request->isMethod('delete')) {
            $params = [];
        } else {
            $params = $this->dencryptData($request->post('params'), $key, $iv);
            unset($all['params']);
        }

        unset($all['/' . $request->path()]);

        $all_params = array_merge($params, compact('time_stamp', 'nonce_str'), $all);

        $sort_string = $this->arrayToString($this->sortAscii($all_params));

        $sign_string = strtoupper(base64_encode($sort_string));  // base64转后转大写
        $signCheck = md5($sign_string);

        if ($signCheck === $sign) {
            $request->merge($params);
            return $next($request);
        }

        dd('签名错误');

//        $sign_type = ($request_time_stamp % 5) % 2;
//        if ($sign_type) {
//            $request_sign = substr($request_sign_str, 0, 32);
//            $request_token = substr($request_sign_str, 32);
//        } else {
//            $request_sign = substr($request_sign_str, -32);
//            $request_token = substr($request_sign_str, 0, -32);
//        }
    }

    /**
     * @param $encryptedData
     * @param $key
     * @param $iv
     * @return mixed
     */
    private function dencryptData($encryptedData, $key, $iv): mixed
    {
        // 解密参数
//        $decryptedData = base64_decode(rawurldecode(urlencode($decryptedData))); // base64 格式密文
        $encryptedData = base64_encode(hex2bin($encryptedData)); // hex 格式密文

        // 非对称解密
//        $decryptedData = @openssl_decrypt($encryptedData, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv); // base64 格式密文
        $decryptedData = @openssl_decrypt($encryptedData, 'AES-128-CBC', $key, OPENSSL_CIPHER_RC2_40, $iv); // hex 格式密文

        if (!$decryptedData) {
            dd('参数错误');
        }

        return json_decode($decryptedData, true);
    }

    private function sortAscii($params = [], $prefix = null)
    {
        $arr = [];

        foreach ($params as $key => $val) {
            if (is_array($val)) {
                $arr = array_merge($arr, $this->sortAscii($val, '_' . $key));
            } else {
                if ($val !== 'null') {
                    $arr[$key . $prefix] = $val;
                }
            }
        }

        return $arr;
    }

    private function arrayToString($arr)
    {
        ksort($arr);
        $str = '';
        foreach ($arr as $key => $val) {
            $str .= $key . '=' . $val . '&';
        }

        $str = preg_replace("/^\&+|\&+$/", '', $str);

        return $str;
    }
}
