<?php

namespace App\Enums;

class UserEnum
{
    // 状态类别
    public const USER_INVALID = -1; //已删除
    public const USER_NORMAL = 0; //正常
    public const USER_FREEZE = 1; //冻结

    public static function getUserStatusName($ipt)
    {
        return match ($ipt) {
            self::USER_INVALID => '已删除',
            self::USER_NORMAL => '正常',
            self::USER_FREEZE => '冻结',
        };
    }

    public const GENDER_UNKNOW = 0; // 未知
    public const GENDER_MAN = 1; // 男
    public const GENDER_WOMAN = 2; // 女

    public static function getUserGenderName($ipt)
    {
        return match ($ipt) {
            self::GENDER_UNKNOW => '未知',
            self::GENDER_MAN => '男',
            self::GENDER_WOMAN => '女',
        };
    }

    public const LOGIN_SUCCESS = 1; // 成功
    public const LOGIN_FAILED = 0; // 失败

    public static function getLoginStatusName($ipt)
    {
        return match ($ipt) {
            self::LOGIN_SUCCESS => '成功',
            self::LOGIN_FAILED => '失败'
        };
    }
}
