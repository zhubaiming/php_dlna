<?php

namespace App\Http\Controllers\Api\Auth;

use App\Facades\SMS;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\Auth\SendVerificationCodeRequest;
use App\Jobs\SendSMSJob;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use WechatTrait;

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
    public function store($validated)
    {
        return true;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

    public function sendVerificationCode(SendVerificationCodeRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();

        $code = mt_rand(100001, 999999);

        SendSMSJob::dispatch(SMS::guard('sms.login'), [$validated['prefix'], $validated['phoneNumber'], $code]);

        return $this->success();
    }

    public function login(Request $request)
    {
        if ($request->missing('origin')) return $this->failed('login missing origin', 999999);

        $loginMethod = strtolower($request->input('origin')) . 'Login';

        $this->validateSafeAll['ip'] = $request->ip();

        if (method_exists($this, $loginMethod)) return $this->{$loginMethod}($request->input());

        return config('app.env') === 'local' ? $this->failed('this not exists method ' . $loginMethod, 999999) : $this->internalError('网络错误');
    }
}
