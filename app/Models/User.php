<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserEnum;
use App\Services\JsonWebToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * 该表将与模型关联
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * 与表关联的主键
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 自增ID的数据类型
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * 是否主动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 设置当前模型使用的数据库连接名
     *
     * @var string
     */
//    protected $connection = '';

    /**
     * 默认属性值
     *
     * @var array
     */
    protected $attributes = [
        'status' => UserEnum::USER_NORMAL
    ];

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
     * 定义存储时间戳的字段名
     */
//    const CREATED_AT = 'sing_in_at';
//    const UPDATED_AT = 'last_login_at';

//    public function getJWTIdentifier(self $user)
//    {
////        $this->jwt->fromUser($user);
//        return JsonWebToken::getToken($this);
////        return JsonWebToken::fromUser($user);
//    }

    /**
     * 获取将存储在 JWT 主题声明中的标识符
     *
     * @return string
     */
    public function getJWTIdentifier(): string
    {
        return $this->getKey();
    }

    /**
     * 返回一个键值数组，其中包含要添加到 JWT 的任何自定义声明
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
            '_id' => $this->getUserId(),
            'created_at' => $this->getAttribute('sing_in_at'),
            'nick_name' => '',
            '__v' => 0,
            'avatar' => ''
        ];

        /**
         * 例子
         *
         * '_id': '5f2918ed59d0b03366c0f0ad,
         * 'email': '111@test.com',
         * 'password': '$2a$10NPgFsgNVtIz3hHI5kdalYouiZe73oyV7bVcnP6vh2CaLR8uASjaOm',
         * 'nickName': '废品回收',
         * 'role': [
         *   '_id': "5e60698bdb60f64b57e36133',
         *   'name': 'normalUser',
         *   '__v': 0.
         *   createdAt': '2020-08-04T08:14:37.470Z',
         *   'access': 'user'
         * ],
         * '__v': 0,
         * 'column': '5f4db92abb821789a5490ed3',
         * 'description': '这是废品回收的简介',
         * 'avatar': '6183ad52fc0f930997b02819',
         * 'createdAt': '2020-08-04T08:14:37.470Z'
         */
    }

    private function getUserId()
    {
        return $this->getAttribute('user_id');
    }
}
