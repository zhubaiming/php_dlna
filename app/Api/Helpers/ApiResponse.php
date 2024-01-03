<?php

namespace App\Api\Helpers;

use App\Enums\HttpCode;
use Illuminate\Support\Facades\Response;

trait ApiResponse
{
    protected int $http_code = HttpCode::HTTP_OK;

//    protected array $responseHeaders = [];
//
//
//    public function notFound($message = 'Not Found!')
//    {
//        return $this->failed($message, HttpCode::HTTP_NOT_FOUND);
//    }
//
//
//    public function created($message = 'Created')
//    {
//        return $this->setStatusCode(HttpCode::HTTP_CREATED)->message($message);
//    }

    /**
     * 响应成功的接口返回
     *
     * @param $data - 要返回的数据
     * @param string $status - 返回提示
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data, string $message = 'success'): \Illuminate\Http\JsonResponse // 成功返回数据
    {
        return $this->status(compact('data'), $message); // compact() - 创建一个包含变量名和它们的值的数组
    }

    /**
     * 响应失败的返回接口
     *
     * @param string $message
     * @param int $code
     * @param int $http_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function failed(string $message, int $code, int $http_code = HttpCode::HTTP_BAD_REQUEST): \Illuminate\Http\JsonResponse
    {
        return $this->setHttpCode($http_code)->message($message, $code);
    }

    /**
     * @param $message
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    private function message($message, $code): \Illuminate\Http\JsonResponse
    {
        return $this->status(['message' => $message], $message, $code);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function internalError(string $message = 'Internal Server Error!'): \Illuminate\Http\JsonResponse
    {
        return $this->failed($message, 999999, HttpCode::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param array $data
     * @param $message
     * @param int $code
     * @param int|null $http_code
     * @return \Illuminate\Http\JsonResponse
     */
    private function status(array $data, $message = null, int $code = 0, int $http_code = null): \Illuminate\Http\JsonResponse
    {
        if (!is_null($http_code)) {
            $this->setHttpCode($http_code);
        }

        $status = [
            'message' => $message,
            'code' => $code
        ];

        $data = array_merge($status, $data);
        return $this->respond($data);
    }

    /**
     * @param $data
     * @param array $header
     * @return \Illuminate\Http\JsonResponse
     */
    private function respond($data, array $header = []): \Illuminate\Http\JsonResponse
    {
        return Response::json($data, $this->getHttpCode(), $header);
    }

    /**
     * @param int $http_code
     * @return $this
     */
    private function setHttpCode(int $http_code): static
    {
        $this->http_code = $http_code;

        return $this;
    }

    /**
     * @return mixed
     */
    private function getHttpCode(): mixed
    {
        return $this->http_code;
    }


}
