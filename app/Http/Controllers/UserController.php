<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JsonWebToken;
use App\Services\Message;
use App\Services\Weixin\MiniApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        $input = $request->post();

        if ('zbm4333' != $input['invitationCode']) {
            return response()->json([
                'code' => 1001,
                'status' => false,
                'result' => [],
                'message' => '邀请码错误，无法进行登录'
            ]);
        } else {
            $code = mt_rand(100000, 999999);

            $message = new Message('aliyun');

            $message->sendMsg('toLogin', [$input['prefix'] . $input['phoneNumber'], $code]);

            if (0 != Redis::hSet('weixin_login_sms', $input['prefix'] . $input['phoneNumber'], $code)) {
                Redis::expire($input['prefix'] . $input['phoneNumber'], 300);
                return response()->json([
                    'code' => 200,
                    'status' => true,
                    'data' => [],
                    'message' => 'ok'
                ]);
            } else {
                return response()->json([
                    'code' => 999999,
                    'status' => true,
                    'data' => [],
                    'message' => '服务器redis故障'
                ]);
            }
        }
    }

    public function validateVerificationCode(Request $request, MiniApp $miniApp)
    {
        $input = $request->post();

        if ($code = Redis::hGet('weixin_login_sms', $input['prefix'] . $input['phoneNumber'])) {
            if ($code === $input['verificationCode']) {
                Redis::hDel('weixin_login_sms', $input['prefix'] . $input['phoneNumber']);

                $wx_session = $miniApp->jscodeToSession($input['wxLoginCode']);

                $user = User::firstOrNew(
                    [
                        'country_code' => $input['prefix'],
                        'pure_phone_number' => $input['phoneNumber']
                    ],
                    [
                        'origin' => '微信小程序',
                        'appId' => 'wx3c1e42206a0ecabd',
                        'wechat_openid' => $wx_session['openid'],
                        'invitation_code' => $input['invitationCode'],
                        'sing_in_at' => $input['invitationCode'],
                        'last_login_at' => $input['invitationCode'],
                        'last_login_ip' => '0.0.0.0'
                    ]
                );

                $user->last_login_ip = '0.0.0.0';

                $user->save();

                return response()->json([
                    'code' => 200,
                    'status' => true,
                    'data' => [
                        'token' => (new JsonWebToken())->createToken()
                    ],
                    'message' => 'ok'
                ]);
            } else {
                return response()->json([
                    'code' => 1003,
                    'status' => false,
                    'result' => [],
                    'message' => '验证码错误，请重新输入'
                ]);
            }
        } else {
            return response()->json([
                'code' => 1002,
                'status' => false,
                'result' => [],
                'message' => '验证码已过期，请重新获取'
            ]);
        }
    }
}
