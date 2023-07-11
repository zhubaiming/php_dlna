<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\v1\wechatMini\OpenController;
use App\Http\Controllers\v1\XmlController;
use App\Http\Controllers\v1\wechatMini\MediaCateController;
use App\Http\Controllers\v1\wechatMini\MediaController;

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
    Route::post('/login', [OpenController::class, 'login']);
    Route::get('/cateList', [MediaCateController::class, 'index']);
    Route::get('/mediaList', [MediaController::class, 'index']);
//    Route::get('/mediaList/{pType}/{type}', [MediaController::class, 'index']);
    Route::get('/mediaDetail', [MediaController::class, 'show']);
});

Route::post('/xml2json', [XmlController::class, 'xml2json']);
Route::post('/parameters2xml', [XmlController::class, 'parameters2xml']);

Route::get('/test', function () {
    for ($i = 31; $i < 102; $i++) {
        $url = 'https://www.netfly.tv/vod/play/101961-1-' . $i . '.html';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);

        $output = curl_exec($curl);

        curl_close($curl);

        $result = substr($output, strpos($output, 'player_aaaa') + 12);

        $result = substr($result, 0, strpos($result, '<'));

        $result = json_decode($result, true);

        $indexUrl = $result['url'];

        $m3u8Prefix = array_reverse(explode('/', $indexUrl))[1];

        \Illuminate\Support\Facades\Log::info($i . ' => \'' . $m3u8Prefix . '\',');
    }

    return 'ok';
});
