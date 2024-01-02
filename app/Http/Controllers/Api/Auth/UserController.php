<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
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

    public function login(Request $request)
    {
        if ($request->missing('origin')) return $this->failed('login missing origin');

        $loginMethod = strtolower($request->input('origin')) . 'Login';

        if (method_exists($this, $loginMethod)) return $this->{$loginMethod}($request->input());

        return config('app.env') === 'local' ? $this->failed('this not exists method ' . $loginMethod) : $this->internalError('网络错误');
    }
}
