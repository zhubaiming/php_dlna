<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MediaCate extends Model
{
    protected $table = 'media_cates';

    public $timestamps = false;

    protected $attributes = [
        'pid' => 0
    ];

    // 匿名全局作用域
    protected static function booted()
    {
        static::addGlobalScope('order_by', function (Builder $builder) {
            $builder->orderBy('sort', 'asc');
//            $builder->with(['withChild'])->orderBy('sort', 'asc');
        });
    }

    /*
     * 局部作用域
     */

    // 只查询公共和视频
    public function scopeVideo(Builder $query)
    {
        $query->whereIn('belonging_to', ['0', '1']);
    }

    // 只查询父级分类
    public function scopeParent(Builder $query): void
    {
        $query->where(['pid' => 0]);
    }

    /*
     * 关联
     */
    public function withManyChild()
    {
        return $this->hasMany(self::class, 'pid', 'id');
    }
}
