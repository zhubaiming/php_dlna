<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

trait HttpTrait
{
    protected $connectTimeout = 60;

    protected $retryTimes = 3;

    protected $retrySleep = 120;

    protected $response_body;

    protected $response_code;

    private array $jscode_to_session_fake = [
        ['session_key' => 'fake/49mPa3V6s/nwGh85MWh5DA==', 'openid' => 'fake/o8OZx526yGHmYDirUvD4lrb53Vkg'],
        ['errcode' => 40163, 'errmsg' => 'fake, code been used, rid: 65927735-29b25d7a-78c318a8'],
        ['errcode' => 40029, 'errmsg' => 'fake, invalid code, rid: 6592775e-4d7adb76-28101323'],
        ['errcode' => 40066, 'errmsg' => 'fake, invalid url, rid: 6592b97d-52ca2b4d-4ecc4d48'],
    ];

    public function getHttp(string $url, array $data = []): void
    {
        if ('local' === config('app.env')) {
            Http::preventStrayRequests();

//            $fake_key = random_int(0, count($this->jscode_to_session_fake) - 1);
            $fake_key = 0;

            Http::fake([
                'qq.com/*' => Http::response($this->jscode_to_session_fake[$fake_key], 200)
            ]);
        }

        $response = $this->baseHttp()->get($url, $data);

        $this->checkHttpResponse($response);
    }

    private function checkHttpResponse($response)
    {
        /**
         * get 方法返回一个 Illuminate\Http\Client\Response 的实例，该实例提供了大量的方法来检查请求的响应：
         *
         * $response->body() : string;
         * $response->json($key = null, $default = null) : array|mixed;
         * $response->object() : object;
         * $response->collect($key = null) : Illuminate\Support\Collection;
         * $response->status() : int;
         * $response->successful() : bool;判断状态码是否是 2xx
         * $response->redirect(): bool;
         * $response->failed() : bool;判断错误码是否是 4xx或5xx
         * $response->clientError() : bool;判断错误码是4xx
         * $response->header($header) : string;
         * $response->headers() : array;
         *
         * Illuminate\Http\Client\Response 对象同样实现了 PHP 的 ArrayAccess 接口，这代表着你可以直接访问响应的 JSON 数据：
         *
         * return Http::get('http://example.com/users/1')['name'];
         * 除了上面列出的响应方法之外，还可以使用以下方法来确定响应是否具有相映的状态码：
         *
         * $response->ok() : bool;                  // 200 OK
         * $response->created() : bool;             // 201 Created
         * $response->accepted() : bool;            // 202 Accepted
         * $response->noContent() : bool;           // 204 No Content
         * $response->movedPermanently() : bool;    // 301 Moved Permanently
         * $response->found() : bool;               // 302 Found
         * $response->badRequest() : bool;          // 400 Bad Request
         * $response->unauthorized() : bool;        // 401 Unauthorized
         * $response->paymentRequired() : bool;     // 402 Payment Required
         * $response->forbidden() : bool;           // 403 Forbidden
         * $response->notFound() : bool;            // 404 Not Found
         * $response->requestTimeout() : bool;      // 408 Request Timeout
         * $response->conflict() : bool;            // 409 Conflict
         * $response->unprocessableEntity() : bool; // 422 Unprocessable Entity
         * $response->tooManyRequests() : bool;     // 429 Too Many Requests
         * $response->serverError() : bool;         // 500 Internal Server Error判断错误码是5xx
         */

        if ($response->successful() && $response->ok()) {
            $this->response_body = $response;
            return true;
        } else {
            $response->throw();
        }
    }

    private function baseHttp(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withHeaders([])
            ->connectTimeout($this->connectTimeout)
            ->retry($this->retryTimes, $this->retrySleep);
    }
}