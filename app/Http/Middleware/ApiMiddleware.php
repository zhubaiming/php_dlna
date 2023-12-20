<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);

            if ($response instanceof Response) {
//                dd($response);
                $data = $response->getContent();

                // 统一格式化 API 返回数据
                $formattedResponse = [
                    'code' => $response->getStatusCode(),
                    'message' => 'ok',
                    'data' => json_decode($data, true)
                ];

                return response()->json($formattedResponse, $response->getStatusCode());
            }

            return $response;
        } catch (ApiException $exception) {
            return $exception->render($request);
        }
    }
}
