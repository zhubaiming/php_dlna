<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MediaSharpnessController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\XmlController;

use App\Http\Controllers\Dlna\ActionsController;

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

Route::prefix('device')->group(function () {
    Route::post('/setAVTransportControlBody', [ActionsController::class, 'setAVTransportControlBody']);
});
