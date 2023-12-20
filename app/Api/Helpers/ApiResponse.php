<?php

namespace App\Api\Helpers;

use App\Enums\HttpCode;
use Illuminate\Support\Facades\Response;

trait ApiResponse
{
    protected $statusCode = HttpCode::HTTP_OK;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respond($data, $header = [])
    {
        return Response::json($data, $this->getStatusCode(), $header);
    }

    public function status($status, array $data, $code = null)
    {
        if ($code) {
            $this->setStatusCode($code);
        }

        $status = [
            'status' => $status,
            'code' => $this->statusCode
        ];

        $data = array_merge($status, $data);
        return $this->respond($data);
    }

    public function failed($message, $code = HttpCode::BAD_REQUEST, $status = 'error')
    {
        // return $this->status('error', [
        //            'message' => $message,
        //            'code' => $code
        //        ]);

        return $this->setStatusCode($code)->message($message, $status);
    }

    public function message($message, $status = 'success')
    {
        return $this->status($status, ['message' => $message]);
    }

    public function internalError($message = 'Internal Server Error!')
    {
        return $this->failed($message, HttpCode::SERVER_ERROR);
    }

    public function created($message = 'Created')
    {
        return $this->setStatusCode(HttpCode::CREATED)->message($message);
    }

    public function success($data, $status = 'success')
    {
        return $this->status($status, compact('data'));
    }

    public function notFound($message = 'Not Found!')
    {
        return $this->failed($message, HttpCode::NOT_FOUND);
    }
}
