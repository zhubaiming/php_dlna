<?php

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

Route::middleware(['api'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('user')->controller(UserController::class)->group(function () {
            Route::post('sendVerificationCode', 'sendVerificationCode');
            Route::post('validateVerificationCode', 'validateVerificationCode');
        });

//        Route::controller(MediaController::class)->group(function () {
//            Route::resource('media', MediaController::class);
//        });

//
        Route::prefix('media')->group(function () {
            Route::resource('conditional', MediaConditionalController::class);
            Route::resource('sharpness', MediaSharpnessController::class);
        });

        Route::resource('media', MediaController::class);
    });
});


Route::prefix('music')->group(function () {
    Route::post('/index', [MusicController::class, 'index']);
});

Route::post('/xml2json', [XmlController::class, 'xml2json']);
