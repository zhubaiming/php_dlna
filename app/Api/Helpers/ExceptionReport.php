<?php

namespace App\Api\Helpers;

use App\Enums\HttpCode;

use Illuminate\Http\Request;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ExceptionReport
{
    use ApiResponse;

    public $exception;

    public $request;

    protected $report;

    public $doReport = [
        AuthenticationException::class => ['未授权', HttpCode::UNAUTHORIZED],
        ModelNotFoundException::class => ['该资源未找到', HttpCode::NOT_FOUND],
        AuthorizationException::class => ['没有此权限', HttpCode::FORBIDDEN],
        ValidationException::class => [],
        UnauthorizedHttpException::class => ['未登录或登录状态失效', HttpCode::UNPROCESSABLE_ENTITY],
        NotFoundHttpException::class => ['没有找到该页面', HttpCode::NOT_FOUND],
        MethodNotAllowedHttpException::class => ['访问方式不正确', HttpCode::METHOD_NOT_ALLOWED],
        QueryException::class => ['参数错误', HttpCode::UNAUTHORIZED]
    ];

    function __construct(Request $request, Exception $exception)
    {
        $this->request = $request;

        $this->exception = $exception;
    }

    public function register($className, callable $callback)
    {
        $this->doReport[$className] = $callback;
    }

    public function shouldReturn()
    {
        foreach (array_keys($this->doReport) as $report) {
            if ($this->exception instanceof $report) {
                $this->report = $report;
                return true;
            }
        }

        return false;
    }

    public static function make(Exception $e)
    {
        return new static(\request(), $e);
    }

    public function report()
    {
        if ($this->exception instanceof ValidationException) {
            $error = array_key_first($this->exception->errors());
            return $this->failed(array_key_first($error), $this->exception->status);
        }

        $message = $this->doReport[$this->report];

        $this->failed($message[0], $message[1]);
    }

    public function prodReport()
    {
        return $this->failed('服务器错误', HttpCode::SERVER_ERROR);
    }
}
