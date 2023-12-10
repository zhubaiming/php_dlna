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

    /**
     * 模型的「引导」方法。
     */
    protected static function booted()
    {
        static::addGlobalScope(new AncientScope());
    }

    /**
     * 只查询【未删除】的作用域
     */
    public function scopeNotDeleted(Builder $query): void
    {
        $query->where(['deleted_flag' => 0]);
    }

    /**
     * 将查询作用域限制为仅包含给定类型的媒体资源
     */
    public function scopeCateId(Builder $query, int $cate_id): void
    {
        $query->where(['cate_id' => $cate_id]);
    }

    public function withEpisode()
    {
        return $this->hasMany(MediaEpisode::class, 'media_id', 'id');
    }

    public function withSharpness()
    {
        return $this->hasMany(MediaSharpness::class, 'media_id', 'id');
    }
}
