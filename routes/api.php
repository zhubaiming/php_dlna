<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\XmlController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('user')->group(function () {
    Route::post('/sendVerificationCode', [UserController::class, 'sendVerificationCode']);
    Route::post('/validateVerificationCode', [UserController::class, 'validateVerificationCode']);
});

Route::prefix('music')->group(function () {
    Route::post('/index', [MusicController::class, 'index']);
});

Route::prefix('media')->group(function () {
    Route::get('/index', [MediaController::class, 'index']);
    Route::get('/show', [MediaController::class, 'show']);
});

Route::post('/xml2json', [XmlController::class, 'xml2json']);
Route::post('/parameters2xml', [XmlController::class, 'parameters2xml']);

Route::prefix('wechatMini')->group(function () {
    Route::post('/login', [OpenController::class, 'login']);
    Route::get('/cateList', function () {
        return response()->json([
            'code' => 200,
            'status' => true,
            'data' => [
                [
                    'id' => 4,
                    'pid' => 1,
                    'name' => '电影'
                ],
                [
                    'id' => 5,
                    'pid' => 1,
                    'name' => '电视剧'
                ],
                [
                    'id' => 6,
                    'pid' => 1,
                    'name' => '综艺'
                ],
                [
                    'id' => 7,
                    'pid' => 1,
                    'name' => '动漫'
                ],
                [
                    'id' => 8,
                    'pid' => 2,
                    'name' => '音乐'
                ],
                [
                    'id' => 8,
                    'pid' => 3,
                    'name' => '相册'
                ]
            ],
            'message' => 'ok'
        ]);
    });
    Route::get('/mediaList', [MediaController::class, 'index']);
//    Route::get('/mediaList/{pType}/{type}', [MediaController::class, 'index']);
    Route::get('/mediaDetail', [MediaController::class, 'show']);
});

