<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JsonWebToken;
use App\Services\Message;
use App\Services\Weixin\MiniApp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        $input = $request->post();

        if ('zbm4333' != $input['invitationCode']) {
            return response()->json([
                'code' => 1001,
                'message' => '邀请码错误，无法进行登录',
                'data' => []
            ]);
        } else {
            $code = mt_rand(100000, 999999);

            $message = new Message('aliyun');

            $message->sendMsg('toLogin', [$input['prefix'] . $input['phoneNumber'], $code]);

            Redis::hSet('weixin_login_sms', $input['prefix'] . $input['phoneNumber'], $code);

            return response()->json([
                'code' => 200,
                'message' => 'ok',
                'data' => []
            ]);
        }
    }

    public function validateVerificationCode(Request $request, MiniApp $miniApp)
    {
        $input = $request->post();

        if ($code = Redis::hGet('weixin_login_sms', $input['prefix'] . $input['phoneNumber'])) {
            if ($code === $input['verificationCode']) {
                Redis::hDel('weixin_login_sms', $input['prefix'] . $input['phoneNumber']);

                $now = date('Y-m-d H:i:s');
                try {
                    $user = User::where(['country_code' => $input['prefix'], 'pure_phone_number' => $input['phoneNumber']])->firstOrFail();

                    $user->last_login_at = $now;
                    $user->last_login_ip = '0.0.0.0';

                    $user->wechat_SDK_version = $input['systemInfo']['SDKVersion'] ?? null;
                    $user->wechat_language = $input['systemInfo']['language'] ?? null;
                    $user->wechat_version = $input['systemInfo']['version'] ?? null;
                    $user->device_model = $input['systemInfo']['model'] ?? null;
                    $user->device_system = $input['systemInfo']['system'] ?? null;

                    $user->save();
                } catch (ModelNotFoundException $e) {
                    $wx_session = $miniApp->jscodeToSession($input['wxLoginCode']);

                    DB::table('user_infos')->insert([
                        'country_code' => $input['prefix'],
                        'pure_phone_number' => $input['phoneNumber'],
                        'invitation_code' => $input['invitationCode'],
                        'origin' => '微信小程序',
                        'appId' => 'wx3c1e42206a0ecabd',
                        'wechat_openid' => $wx_session['openid'],
                        'wechat_unionid' => $wx_session['unionid'] ?? null,
                        'wechat_SDK_version' => $input['systemInfo']['SDKVersion'] ?? null,
                        'wechat_language' => $input['systemInfo']['language'] ?? null,
                        'wechat_version' => $input['systemInfo']['version'] ?? null,
                        'device_model' => $input['systemInfo']['model'] ?? null,
                        'device_system' => $input['systemInfo']['system'] ?? null,
                        'sing_in_at' => $now,
                        'last_login_at' => $now,
                        'last_login_ip' => '0.0.0.0',
                    ]);
                }

                return response()->json([
                    'code' => 200,
                    'message' => 'ok',
                    'data' => [
                        'token' => (new JsonWebToken())->createToken()
                    ]
                ]);
            } else {
                return response()->json([
                    'code' => 1003,
                    'message' => '验证码错误，请重新输入',
                    'data' => []
                ]);
            }
        } else {
            return response()->json([
                'code' => 1002,
                'message' => '验证码已过期，请重新获取',
                'data' => []
            ]);
        }
    }
}
