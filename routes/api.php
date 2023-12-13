<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
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
    Route::prefix('sharpness')->group(function () {
        Route::get('/index', [MediaSharpnessController::class, 'index']);
    });
});

Route::post('/xml2json', [XmlController::class, 'xml2json']);

Route::get('/test', function (Request $request) {
//    $jwt = new \App\Services\JsonWebToken();
//    return $jwt->createToken();

    var_dump($request->ip());

    var_dump(getenv('X-Forwarded-Proto'));
    var_dump($_SERVER['X-Forwarded-Proto']);

    var_dump(getenv('X-Forwarded-For'));
    var_dump($_SERVER['X-Forwarded-For']);

    exit();
});

Route::post('/test1', function (Request $request) {
    $lists = $request->post();

    foreach ($lists as $key => $list) {
        \Illuminate\Support\Facades\DB::table('sources_area')->insert([
            'name' => $list,
            'sort' => $key + 1
        ]);
    }
});
