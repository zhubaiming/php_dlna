<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\MediaConditionalController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MediaSharpnessController;
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

Route::get('/test', function () {
    dd(DB::table('media_copy2')->where(['origin_id' => trim(123), 'origin' => '欧乐影院'])->value('id'));
});

Route::post('/test1', [UserController::class, 'test']);

//Route::prefix('v1')->group(function () {
//    Route::prefix('user')->controller(UserController::class)->group(function () {
//        Route::post('sendVerificationCode', 'sendVerificationCode');
//        Route::post('validateVerificationCode', 'validateVerificationCode');
//    });
//
//    Route::prefix('media')->group(function () {
//        Route::prefix('conditional')->group(function () {
//            Route::get('index', [MediaConditionalController::class, 'index']);
//        });
//
//        Route::get('/index', [MediaController::class, 'index']);
//        Route::get('/show', [MediaController::class, 'show']);
//        Route::prefix('sharpness')->group(function () {
//            Route::get('/index', [MediaSharpnessController::class, 'index']);
//        });
//    });
//});
