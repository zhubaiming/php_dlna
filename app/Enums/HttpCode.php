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
     * 对创建新资源的 POST 操作进行响应，应该带着指向新资源地址的 Location 头
     */
    public const HTTP_CREATED = 201;

    /**
     * @Message("Accepted")
     * 服务器接受了请求，但是还未处理，响应中应该包含相应的指示信息，告诉客户端该去哪里查询关于本次请求的信息
     */
    public const HTTP_ACCEPTED = 202;
    public const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;

    /**
     * @Message("No Content")
     * 对不会返回响应体的成功请求进行响应（比如 DELETE 请求）
     */
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_RESET_CONTENT = 205;
    public const HTTP_PARTIAL_CONTENT = 206;
    public const HTTP_MULTI_STATUS = 207;          // RFC4918
    public const HTTP_ALREADY_REPORTED = 208;      // RFC5842
    public const HTTP_IM_USED = 226;               // RFC3229
    public const HTTP_MULTIPLE_CHOICES = 300;

    /**
     * @Message("Moved Permanently")
     * 被请求的资源已永久移动到新位置
     */
    public const HTTP_MOVED_PERMANENTLY = 301;

    /**
     * @Message("Found")
     * 请求的资源现在临时从不同的 URI 响应请求
     */
    public const HTTP_FOUND = 302;

    /**
     * @Message("See Other")
     * 对应当前请求的响应可以在另一个 URI 上被找到，客户端应该使用 GET 方法进行请求。比如在创建已经被创建的资源时，可以返回 303
     */
    public const HTTP_SEE_OTHER = 303;

    /**
     * @Message("Not Modified")
     * HTTP 缓存 header 生效的时候用
     */
    public const HTTP_NOT_MODIFIED = 304;
    public const HTTP_USE_PROXY = 305;
    public const HTTP_RESERVED = 306;

    /**
     * @Message("Temporary Redirect")
     * 对应当前请求的响应可以在另一个 URI 上被找到，客户端应该保持原有的请求方法进行请求
     */
    public const HTTP_TEMPORARY_REDIRECT = 307;
    public const HTTP_PERMANENTLY_REDIRECT = 308;  // RFC7238

    /**
     * @Message("Bad Request")
     * 请求异常，比如请求中的 body 无法解析
     */
    public const HTTP_BAD_REQUEST = 400;

    /**
     * @Message("Unauthorized")
     * 没有进行认证或认证非法
     */
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_PAYMENT_REQUIRED = 402;

    /**
     * @Message("Forbidden")
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
     */
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_NOT_ACCEPTABLE = 406;
    public const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
    public const HTTP_REQUEST_TIMEOUT = 408;
    public const HTTP_CONFLICT = 409;

    /**
     * @Message("Gone")
     * 表示当前请求的资源不再可用。当调用老版本 API 的时候很有用
     */
    public const HTTP_GONE = 410;
    public const HTTP_LENGTH_REQUIRED = 411;
    public const HTTP_PRECONDITION_FAILED = 412;
    public const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
    public const HTTP_REQUEST_URI_TOO_LONG = 414;

    /**
     * @Message("Unsupported Media Type")
     * 如果请求中的内容类型是错误的
     */
    public const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    public const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    public const HTTP_EXPECTATION_FAILED = 417;
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
     * 服务器不支持当前请求所需要的某个功能
     */
    public const HTTP_NOT_IMPLEMENTED = 501;

    /**
     * @Message("Bad Gateway")
     * 作为网关或者代理工作的服务器尝试执行请求时，从上有服务器收到无效的响应
     */
    public const HTTP_BAD_GATEWAY = 502;

    /**
     * @Message("Service Unavailable")
     * 由于临时的服务器维护或过载，服务器当前无法处理请求。这个情况是临时的，并且将在一段时间以后恢复。如果能够预计延迟时间，
     * 那么响应中可以包含一个 Retry-After 头用以标明这个延迟时间（内容可以为数字，单位为秒；
     * 或者是一个 HTTP 协议指定的时间格式）。如果没有给出这个 Retry-After 信息，那么客户端应当以处理 500 响应的方式处理它
     */
    public const HTTP_SERVICE_UNAVAILABLE = 503;
    public const HTTP_GATEWAY_TIMEOUT = 504;
    public const HTTP_VERSION_NOT_SUPPORTED = 505;
    public const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;                        // RFC2295
    public const HTTP_INSUFFICIENT_STORAGE = 507;                                        // RFC4918
    public const HTTP_LOOP_DETECTED = 508;                                               // RFC5842
    public const HTTP_NOT_EXTENDED = 510;                                                // RFC2774
    public const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;                             // RFC6585
}
