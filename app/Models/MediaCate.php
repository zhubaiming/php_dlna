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
        static::addGlobalScope('sort', function (Builder $builder) {
            $builder->orderBy('sort', 'asc');
        });
    }

    // 关联

    public function withChildren()
    {
        return $this->hasMany(MediaCate::class, 'pid', 'id');
    }
}
