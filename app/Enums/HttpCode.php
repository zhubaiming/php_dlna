<?php

namespace App\Enums;

class HttpCode
{
    public const HTTP_CONTINUE = 100;
    public const HTTP_SWITCHING_PROTOCOLS = 101;
    public const HTTP_PROCESSING = 102;            // RFC2518
    public const HTTP_EARLY_HINTS = 103;           // RFC8297

    /**
     * @Message("OK")
     * 对成功的 GET、PUT、PATCH 或 DELETE 操作进行响应，也可以被用在不创建新资源的 POST 操作上
     */
    public const HTTP_OK = 200;

    /**
     * @Message("Created")
     * 请求成功，资源被正确创建
     * 比如，POST 用户名、密码正确创建了一个用户
     * 对创建新资源的 POST 操作进行响应，应该带着指向新资源地址的 Location 头
     */
    public const HTTP_CREATED = 201;

    /**
     * @Message("Accepted")
     * 请求成功，但结果正在处理中，没法返回对应的结果
     * 比如，请求一个需要大量计算的结果，但是并没有计算结束时，可以返回这个，这时候客户端可以通过轮训机制继续请求
     * 服务器接受了请求，但是还未处理，响应中应该包含相应的指示信息，告诉客户端该去哪里查询关于本次请求的信息
     */
    public const HTTP_ACCEPTED = 202;

    /**
     * @Message("Non Authoritative Information")
     * 一般用不到
     * 请求的代理服务器修改了源服务器返回的 200 中的内容
     * 比如，通过代理服务器向服务器A请求用户信息，服务器A正常响应，但代理服务器命中了缓存并返回了自己的缓存内容，这时返回 203 告诉客户端这部分信息不一定是最新的，客户端自行判断并处理
     */
    public const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;

    /**
     * @Message("No Content")
     * 请求成功，但没有需要返回的内容
     * 比如，删除某个用户，删除成功可以返回 204
     * 对不会返回响应体的成功请求进行响应（比如 DELETE 请求）
     */
    public const HTTP_NO_CONTENT = 204;

    /**
     * @Message("Reset Content")
     * 一般用不到
     * 请求成功，但要求请求者重置视图
     * 比如，请求删除某个用户，服务器返回 205 的话，就刷新现在的用户列表
     */
    public const HTTP_RESET_CONTENT = 205;

    /**
     * @Message("Partial content")
     * 请求成功，但根据请求头只返回了部分内容
     * 比如，下载一部影片，共有10部分，客户端把请求也分成了10次(防止一次请求过大)，这时服务器可以返回 206 并在其头部告诉客户端这时那一部分，然后再根据这个信息进行拼装
     */
    public const HTTP_PARTIAL_CONTENT = 206;
    public const HTTP_MULTI_STATUS = 207;          // RFC4918
    public const HTTP_ALREADY_REPORTED = 208;      // RFC5842
    public const HTTP_IM_USED = 226;               // RFC3229

    /**
     * @Message("Multiple Choices")
     * 请求成功，但结果有多重选择
     * 比如，下载一部影片，服务器有 avi、mp4 等格式，这时可以返回 300，并在 body 里告知有哪些格式，然后客户端可以根据这些格式再去请求
     */
    public const HTTP_MULTIPLE_CHOICES = 300;

    /**
     * @Message("Moved Permanently")
     * 请求成功，但被请求的资源已永久移动到新位置
     * 比如，客户端要下载葫芦娃，但是由于旧的存储服务商涨价了，现在要使用新的存储服务了，要去新地址下载，这时可以返回 301，并在 header 的 Location 中告知新的地址，以后也应当到这个地址下载
     */
    public const HTTP_MOVED_PERMANENTLY = 301;

    /**
     * @Message("Found")
     * 请求成功，请求的资源现在临时从不同的 URI 响应请求
     */
    public const HTTP_FOUND = 302;

    /**
     * @Message("See Other")
     * 请求成功，对应当前请求的响应可以在另一个 URI 上被找到，客户端应该使用 GET 方法进行请求。比如在创建已经被创建的资源时，可以返回 303
     */
    public const HTTP_SEE_OTHER = 303;

    /**
     * @Message("Not Modified")
     * 请求成功，但请求的资源并没有被修改过
     * 比如，客户端发送请求想看看5.20后的情侣信息，服务器查询没有新的情侣信息产生，这时可以返回 304，告诉客户端继续使用旧的数据
     * HTTP 缓存 header 生效的时候用
     */
    public const HTTP_NOT_MODIFIED = 304;

    /**
     * @Message("Use Proxy")
     * 请求的资源必须通过代理访问
     * 比如，客户端想请求服务器A上的新的iPhone信息，但是需要通过代理服务器才能访问，如果直接请求了服务器A，没有经过代理服务器，这时候服务器A就可以返回 305 告诉客户端应当访问代理服务器
     */
    public const HTTP_USE_PROXY = 305;

    /**
     * 不用了
     */
    public const HTTP_RESERVED = 306;

    /**
     * @Message("Temporary Redirect")
     * 对应当前请求的响应可以在另一个 URI 上被找到，客户端应该保持原有的请求方法进行请求
     */
    public const HTTP_TEMPORARY_REDIRECT = 307;

    /**
     * @Message("Permanently Redirect")
     * 同 301，并要求请求方式不变
     */
    public const HTTP_PERMANENTLY_REDIRECT = 308;  // RFC7238

    /**
     * @Message("Bad Request")
     * 请求异常
     * 比如，请求中的 body 无法解析
     */
    public const HTTP_BAD_REQUEST = 400;

    /**
     * @Message("Unauthorized")
     * 没有进行认证或认证非法
     * 比如，请求的时候没有带上 Token
     */
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_PAYMENT_REQUIRED = 402;

    /**
     * @Message("Forbidden")
     * 请求的资源不允许访问
     * 比如，普通用户的 Token 去请求管理员才能访问的资源
     * 服务器已经理解请求，但是拒绝执行它
     */
    public const HTTP_FORBIDDEN = 403;

    /**
     * @Message("Not Found")
     * 请求一个不存在的资源
     */
    public const HTTP_NOT_FOUND = 404;

    /**
     * @Message("Method Not Allowed")
     * 所请求的 HTTP 方法不允许当前认证用户访问
     * 比如，服务器只实现了 PATCH 来局部更新资源，并有没实现 PUT 来替换资源，但客户端使用了 PUT，这时可以返回 405 来告知客户端没有实现对 PUT 的相关处理
     */
    public const HTTP_METHOD_NOT_ALLOWED = 405;

    /**
     * @Message("Not Acceptable")
     * 请求的资源不符合要求
     * 比如，客户端 header 里请求 JSON 格式的数据，但是服务器只有 XML 格式的数据，这时可以返回 406
     */
    public const HTTP_NOT_ACCEPTABLE = 406;

    /**
     * @Message("Proxy Authentication Required")
     * 同 401，但是要求必须去同代理服务器进行认证
     */
    public const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;

    /**
     * @Message("Request Timeout")
     * 客户端请求超时
     * 比如，客户端想 POST 创建一个用户，虽然建立了连接，但是网络不好，服务器在规定时间内没有得到我们的请求信息，这时可以返回 408，告知客户端超时，需要重新发送请求
     */
    public const HTTP_REQUEST_TIMEOUT = 408;

    /**
     * @Message("Conflict")
     * 请求冲突
     * 比如，服务器要求不同用户不能崇明，服务器已经有一个名叫小A的用户，这时客户端又想创建一个名叫小A的用户，服务器可以返回 409，告知客户端冲突，也可以在 body 中明确告知是什么冲突
     */
    public const HTTP_CONFLICT = 409;

    /**
     * @Message("Gone")
     * 表示当前请求的资源不再可用。当调用老版本 API 的时候很有用
     * 比如，客户端下载葫芦娃，但是因为版权被删了，下载不了了，这时可以返回 410，告知客户端洗洗睡了
     */
    public const HTTP_GONE = 410;

    /**
     * @Message("Length Required")
     * 没有提供请求资源的长度
     * 比如，客户端下载葫芦娃，服务器只允许客户端分部分下载，客户端如果不告诉服务器要下载哪部分，这时可以返回 411
     */
    public const HTTP_LENGTH_REQUIRED = 411;

    /**
     * @Message("Precondition Failed")
     * 请求的资源不符合请求头中的 IF-* 的某些条件
     * 比如，客户端下载葫芦娃，然后在请求头告知服务器要5.20后更新过的，服务器没有，这时可以返回412
     */
    public const HTTP_PRECONDITION_FAILED = 412;

    /**
     * @Message("Request Entity Too Large")
     * 请求体过大
     * 比如，服务器要求上传文件不能超过 5M，但是客户端 POST 了 10M，这时可以返回 413
     */
    public const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;

    /**
     * @Message("Request URI Too Long")
     * 请求的 URI 太长
     * 比如，客户端提供了太多的 Query 参数，以至于超过了服务器的限制，这时可以返回 414
     */
    public const HTTP_REQUEST_URI_TOO_LONG = 414;

    /**
     * @Message("Unsupported Media Type")
     * 不支持的媒体类型
     * 比如，客户端上传了一张七娃的GIF动图，而服务器只允许上传PNG图片，这时可以返回 415
     * 如果请求中的内容类型是错误的
     */
    public const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;

    /**
     * @Message("Requested Range Not Satisfiable")
     * 请求的区间无效
     * 比如，客户端分不分下载时，请求葫芦娃的10分钟到12分钟的内容，但是这部葫芦娃只有1分钟的内容，这时可以返回 416
     */
    public const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    /**
     * @Message("Expectation Failed")
     * 预期错误
     * 服务器没法满足客户端在请求头里的 Expect 相关的信息
     */
    public const HTTP_EXPECTATION_FAILED = 417;

    /**
     * @Message("I Am A Teapot")
     * 我是个茶壶
     * 这是一个愚人节的玩笑，这个状态码就是用来搞笑的
     */
    public const HTTP_I_AM_A_TEAPOT = 418;                                               // RFC2324
    public const HTTP_MISDIRECTED_REQUEST = 421;                                         // RFC7540

    /**
     * @Message("Unprocessable Entity")
     * 用来表示校验错误
     */
    public const HTTP_UNPROCESSABLE_ENTITY = 422;                                        // RFC4918
    public const HTTP_LOCKED = 423;                                                      // RFC4918
    public const HTTP_FAILED_DEPENDENCY = 424;                                           // RFC4918
    public const HTTP_TOO_EARLY = 425;                                                   // RFC-ietf-httpbis-replay-04
    public const HTTP_UPGRADE_REQUIRED = 426;                                            // RFC2817
    public const HTTP_PRECONDITION_REQUIRED = 428;                                       // RFC6585

    /**
     * @Message("Too Many Requests")
     * 由于请求频次达到上限而被拒绝访问
     */
    public const HTTP_TOO_MANY_REQUESTS = 429;                                           // RFC6585
    public const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;                             // RFC6585
    public const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;                               // RFC7725

    /**
     * @Message("Internal Server Error")
     * 服务器遇到了一个未曾预料的情况，导致了它无法完成对请求的处理
     */
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * @Message("Not Implemented")
     * 请求还没被实现
     * 比如，客户端请求一个接口来自动拒绝经理的要求，但是这个接口只是美好的想想，并没有被实现，这时可以返回 501
     * 服务器不支持当前请求所需要的某个功能
     */
    public const HTTP_NOT_IMPLEMENTED = 501;

    /**
     * @Message("Bad Gateway")
     * 网关错误
     * 比如，客户端向服务器A请求下载葫芦娃，但是A其实只是一个代理服务器，他得向B请求葫芦娃，但是不知道为啥B不搭理他或者给他错误，这时候A可以返回 502 表示B这家回傲娇了
     * 作为网关或者代理工作的服务器尝试执行请求时，从上游服务器收到无效的响应
     */
    public const HTTP_BAD_GATEWAY = 502;

    /**
     * @Message("Service Unavailable")
     * 由于临时的服务器维护或过载，服务器当前无法处理请求。这个情况是临时的，并且将在一段时间以后恢复
     * 如果能够预计延迟时间，那么响应中可以包含一个 Retry-After 头用以标明这个延迟时间（内容可以为数字，单位为秒；
     * 或者是一个 HTTP 协议指定的时间格式）。如果没有给出这个 Retry-After 信息，那么客户端应当以处理 500 响应的方式处理它
     */
    public const HTTP_SERVICE_UNAVAILABLE = 503;

    /**
     *  @Message("Gateway Timeout")
     * 同 502，但是表示B不理A，超时了
     */
    public const HTTP_GATEWAY_TIMEOUT = 504;

    /**
     *  @Message("Version Not Supported")
     * 请求的 HTTP 版本不支持
     * 比如，现在强行根据 HTTP 1000 来请求
     */
    public const HTTP_VERSION_NOT_SUPPORTED = 505;
    public const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;                        // RFC2295
    public const HTTP_INSUFFICIENT_STORAGE = 507;                                        // RFC4918
    public const HTTP_LOOP_DETECTED = 508;                                               // RFC5842
    public const HTTP_NOT_EXTENDED = 510;                                                // RFC2774
    public const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;                             // RFC6585
}
