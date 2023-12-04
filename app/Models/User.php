<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user_infos';

    /**
     * The attributes that are mass assignable.
     * 定义要使哪些模型属性可批量分配【使用 create 创建数据必须定义】
     *
     * @var array<int, string>
     */
    protected $fillable = [
//        'name',
//        'email',
//        'password',
        'country_code',
        'pure_phone_number',
        'origin',
        'appId',
        'wechat_openid',
        'code',
        'wechat_session_key',
        'wechat_unionid',
        'last_login_ip',
        'invitation_code',
    ];

//    /**
//     * The attributes that should be hidden for serialization.
//     *
//     * @var array<int, string>
//     */
//    protected $hidden = [
//        'password',
//        'remember_token',
//    ];
//
//    /**
//     * The attributes that should be cast.
//     *
//     * @var array<string, string>
//     */
//    protected $casts = [
//        'email_verified_at' => 'datetime',
//        'password' => 'hashed',
//    ];

    /**
     * 指示模型是否主动维护时间戳。
     *
     * @var bool
     */
//    public $timestamps = false;

    const CREATED_AT = 'sing_in_at';
    const UPDATED_AT = 'last_login_at';
}
