<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        Auth::guard('jwt')->attempt();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::guard('api')->user();

        return $this->success(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function login(UserRequest $request)
    {
        $validated = $request->validated();

        if (Auth::guard('api')->attempt([$validated['prefix'] . $validated['phoneNumber'], $validated['verificationCode']])) {

        }

        // $input = $request->input();
        //
        //        if ($code = Redis::hGet('weixin_login_sms', $input['prefix'] . $input['phoneNumber'])) {
        //            if ($code === $input['verificationCode']) {
        //                Redis::hDel('weixin_login_sms', $input['prefix'] . $input['phoneNumber']);
        //
        //                $time = time();
        //                $now = date('Y-m-d H:i:s', $time);
        //
        //                $wx_session = $miniApp->jscodeToSession($input['wxLoginCode']);
        //
        //                try {
        //                    $user = User::where(['country_code' => $input['prefix'], 'pure_phone_number' => $input['phoneNumber']])->firstOrFail();
        //
        //                    $user->last_login_at = $now;
        //                    $user->last_login_ip = $request->ip();
        //
        //                    $user->wechat_openid = $wx_session['openid'];
        //                    $user->wechat_unionid = $wx_session['unionid'] ?? null;
        //
        //                    $user->wechat_SDK_version = $input['systemInfo']['SDKVersion'] ?? $user->wechat_SDK_version;
        //                    $user->wechat_language = $input['systemInfo']['language'] ?? $user->wechat_language;
        //                    $user->wechat_version = $input['systemInfo']['version'] ?? $user->wechat_version;
        //                    $user->device_model = $input['systemInfo']['model'] ?? $user->device_model;
        //                    $user->device_system = $input['systemInfo']['system'] ?? $user->device_system;
        //
        //                    $user->save();
        //                } catch (ModelNotFoundException $e) {
        //                    $id = DB::table('user_infos')->insertGetId([
        //                        'country_code' => $input['prefix'],
        //                        'pure_phone_number' => $input['phoneNumber'],
        //                        'origin' => '微信小程序',
        //                        'appId' => 'wx3c1e42206a0ecabd',
        //                        'wechat_openid' => $wx_session['openid'],
        //                        'wechat_unionid' => $wx_session['unionid'] ?? null,
        //                        'wechat_SDK_version' => $input['systemInfo']['SDKVersion'] ?? null,
        //                        'wechat_language' => $input['systemInfo']['language'] ?? null,
        //                        'wechat_version' => $input['systemInfo']['version'] ?? null,
        //                        'device_model' => $input['systemInfo']['model'] ?? null,
        //                        'device_system' => $input['systemInfo']['system'] ?? null,
        //                        'sing_in_at' => $now,
        //                        'last_login_at' => $now,
        //                        'last_login_ip' => $request->ip()
        //                    ]);
        //
        //                    $user = User::find($id);
        //                }
        //
        //                $token = JsonWebToken::getToken([
        //                    'iss' => env('WX_MINIAPP_APP_ID'),
        //                    'iat' => $time,
        //                    'exp' => $time + 7200,
        ////                    'nbf' => $time + 60,
        //                    'sub' => config('app.url'),
        //                    'aud' => '',
        //                    'jti' => md5($user->origin . $user->wechat_openid . $user->device_model),
        //                    'id' => $user->id
        //                ]);
        //
        //                Redis::set('User_login_' . $user->id, $user->toJson());
        //
        //                return response()->json([
        //                    'code' => 200,
        //                    'message' => 'ok',
        //                    'data' => [
        //                        'token' => $token
        //                    ]
        //                ]);
        //            } else {
        //                return response()->json([
        //                    'code' => 1003,
        //                    'message' => '验证码错误，请重新输入',
        //                    'data' => []
        //                ]);
        //            }
        //        } else {
        //            return response()->json([
        //                'code' => 1002,
        //                'message' => '验证码已过期，请重新获取',
        //                'data' => []
        //            ]);
        //        }
    }
}
