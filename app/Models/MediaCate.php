<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MediaCate extends Model
{
    protected $table = 'cate';

    // 匿名全局作用域
    protected static function booted()
    {
        static::addGlobalScope('order_by', function (Builder $builder) {
            $builder->with(['withChild'])->orderBy('sort', 'asc');
        });
    }

    // 关联
    public function withChild()
    {
        return $this->hasMany(self::class, 'pid', 'id');
    }
}
