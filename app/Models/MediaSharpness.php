<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaSharpness extends Model
{
    use HasFactory;

    protected $table = 'media_sharpness';

    public $timestamps = false;

    /**
     * 将查询作用域限制为仅包含给定类型的媒体资源
     */
    public function scopeMediaId(Builder $query, int $media_id): void
    {
        $query->where(['media_id' => $media_id]);
    }

    public function withUrls()
    {
        return $this->hasOne(MediaUrl::class, 'sharpness_id', 'id');
    }
}
