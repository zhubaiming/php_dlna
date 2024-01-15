<?php

namespace App\Models;

use App\Models\Scopes\AncientScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    public $table = 'media';

    public $timestamps = false;

//    // 加载全局作用域
//
//    protected static function booted()
//    {
//        static::addGlobalScope(new AncientScope());
//    }

    // 匿名全局作用域
    protected static function booted()
    {
        static::addGlobalScope('order_by', function (Builder $builder) {
            $builder->orderBy('year', 'desc')->orderBy('updated_at', 'desc');
        });
    }

    // 局部作用域

    /**
     * 只查询【未删除】的作用域
     */
    public function scopeNotDeleted(Builder $query): void
    {
        $query->where(['del_flag' => 0]);
    }

    /**
     * 将查询作用域限制为仅包含给定类型的媒体资源
     */
    public function scopeCateId(Builder $query, int $cate_id): void
    {
        if (0 != $cate_id) {
            $query->where(['cate_id' => $cate_id]);
        }
    }

    public function scopeArea(Builder $query, int $area_id): void
    {
        if (0 != $area_id) {
            $query->where(['area_id' => $area_id]);
        }
    }

    public function scopeYear(Builder $query, int $year): void
    {
        if (0 != $year) {
            $query->where(['year' => $year]);
        }
    }

    public function scopeTypeId(Builder $query, int $type_id): void
    {
        if (0 != $type_id) {
            $query->where(['type_id' => $type_id]);
        }
    }

    public function scopeName(Builder $query, $name): void
    {
        if (null != $name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }
    }

    // 关联

    public function withEpisode()
    {
        return $this->hasMany(MediaEpisode::class, 'media_id', 'id');
    }

    public function withSharpness()
    {
        return $this->hasMany(MediaSharpness::class, 'media_id', 'id');
    }
}
