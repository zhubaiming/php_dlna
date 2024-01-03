<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Authenticatable;

class JsonWebToken
{
    private static array $header = [
        'alg' => 'SH256', // 生成signature的算法
        'typ' => 'JWT'    // 类型
    ];

    /**
     * @param array $payload jwt载荷  格式如下非必须
     *
     * [
     * 'iss' => '', // issuer 签发人 - 该JWT的签发者
     * 'exp' => '', // expiration time 过期时间 - 过期时间
     * 'sub' => '', // subject 主题 - 面向的用户
     * 'aud' => '', // audience 受众
     * 'nbf' => '', // Not Before 生效时间 - 该时间之前不接收处理该Token
     * 'iat' => '', // Issued At 签发时间 - 签发时间
     * 'jti' => ''  // JWT ID 编号 - 该Token唯一标识
     * ]
     *
     * https://juejin.cn/post/7059151598643052575
     *
     * @return false|string
     */
    public static function getToken(Authenticatable $user)
    {
        $base64header = self::base64UrlEncode(json_encode(self::$header, JSON_UNESCAPED_UNICODE));

        $base64payload = self::base64UrlEncode(json_encode(self::setPayload($user), JSON_UNESCAPED_UNICODE));

        return $base64header . '.' . $base64payload . '.' . self::signature($base64header . '.' . $base64payload, self::$header['alg']);
    }

    private static function setPayload($user): array
    {
        $time = time();

        return [
            'iss' => $user->app_id, // issuer 签发人 - 该JWT的签发者
            'iat' => $time, // Issued At 签发时间 - 签发时间
            'exp' => $time + 7200, // expiration time 过期时间 - 过期时间
            'sub' => config('app.url'), // subject 主题 - 面向的用户
            'aud' => '', // audience 受众
            'nbf' => '', // Not Before 生效时间 - 该时间之前不接收处理该Token
            'jti' => md5($user->id),  // JWT ID 编号 - 该Token唯一标识
            'data' => [ // 自定义数据
                '_id' => '',
                'email' => '',
                'password' => '',
                'nickName' => '',
                'role' => [
                    '_id' => '',
                    'name' => '',
                    '__v' => '',
                    'createdAt' => '',
                    'access' => '',
                ],
                '__v' => 0,
                'column' => '',
                'description' => '',
                'avatar' => '',
                'createdAt' => '',
                /**
                 * 例子
                 *
                 * '_id': '5f2918ed59d0b03366c0f0ad,
                 * 'email': '111@test.com',
                 * 'password': '$2a$10NPgFsgNVtIz3hHI5kdalYouiZe73oyV7bVcnP6vh2CaLR8uASjaOm',
                 * 'nickName': '废品回收',
                 * 'role': [
                 *   '_id': "5e60698bdb60f64b57e36133',
                 *   'name': 'normalUser',
                 *   '__v': 0.
                 *   createdAt': '2020-08-04T08:14:37.470Z',
                 *   'access': 'user'
                 * ],
                 * '__v': 0,
                 * 'column': '5f4db92abb821789a5490ed3',
                 * 'description': '这是废品回收的简介',
                 * 'avatar': '6183ad52fc0f930997b02819',
                 * 'createdAt': '2020-08-04T08:14:37.470Z'
                 */
            ],
        ];
    }

    public static function verifyToken(string $Token)
    {
        $tokens = explode('.', $Token);
        if (count($tokens) != 3) {
            return false;
        }

        list($base64header, $base64payload, $sign) = $tokens;

        // 获取jwt算法
        $base64decodeheader = json_decode(self::base64UrlDecode($base64header), JSON_OBJECT_AS_ARRAY);
        if (empty($base64decodeheader['alg'])) return false;

        // 签名验证
        if (self::signature($base64header . '.' . $base64payload, $base64decodeheader['alg']) !== $sign) return false;

        $payload = json_decode(self::base64UrlDecode($base64payload), JSON_OBJECT_AS_ARRAY);

        // 签发时间大于当前服务器时间验证失败
        if (isset($payload['iat']) && $payload['iat'] > time()) return false;

        // 过期时间小宇当前服务器时间验证失败
        if (isset($payload['exp']) && $payload['exp'] < time()) return false;

        // 该nbf时间之前不接收处理该Token
        if (isset($payload['nbf']) && $payload['nbf'] > time()) return false;

        return $payload;
    }

    /**
     * https://jwt.io/ 中base64UrlEncode编码实现
     *
     * @param string $input 需要编码的字符串
     * @return array|string|string[]
     */
    private static function base64UrlEncode(string $input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    /**
     * https://jwt.io/ 中base64UrlEncode解码实现
     *
     * @param string $input 需要解码的字符串
     * @return false|string
     */
    private static function base64UrlDecode(string $input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $addlen = 4 - $remainder;
            $input .= str_repeat('=', $addlen);
        }

        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * https://jwt.io/ 中HMACSHA256签名实现
     *
     * @param string $input 为base64UrlEncode(header).".".base64UrlEncode(payload)
     * @param string $key
     * @param string $alg 算法方式
     * @return array|string|string[]
     */
    private static function signature(string $input, string $alg = 'SH256')
    {
        $alg_config = [
            'SH256' => 'sha256'
        ];

        return self::base64UrlEncode(hash_hmac($alg_config[$alg], $input, config('app.key'), true));
    }

    public function createToken()
    {
        return 'aaaaaa';
    }
}
