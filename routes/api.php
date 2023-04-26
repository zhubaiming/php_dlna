<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\v1\wechatMini\OpenController;
use App\Http\Controllers\v1\XmlController;

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

Route::prefix('wechatMini')->group(function () {
    Route::post('/login', [OpenController::class], 'login');
});

Route::post('/xml2json', [XmlController::class, 'xml2json']);
Route::post('/parameters2xml', [XmlController::class, 'parameters2xml']);
