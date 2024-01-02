<?php

namespace App\Services;

class MistID
{
    // 手机号转2进制35位
    // 时间戳36位
    private static $saltBit = 8; // 随机因子1二进制位数

    private static $saltShift = 8; // 随机因子2二进制位数

    private static $saltA = 0; // 随机因子1

    private static $saltB = 0; // 随机因子2

    public static function generate(int $id): int
    {
        self::$saltA = random_int(self::$saltA, 255);

        self::$saltB = random_int(self::$saltB, 255);

        return (int)($id << (self::$saltBit + self::$saltShift) | (self::$saltA << self::$saltBit) | self::$saltB);
    }
}
