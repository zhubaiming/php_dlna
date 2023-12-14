<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourcesCate extends Model
{
    use HasFactory;

    public $table = 'sources_cate';

    // 匿名全局作用域

    protected static function booted()
    {
        static::addGlobalScope('sort', function (Builder $builder) {
            $builder->orderBy('sort', 'asc');
        });
    }

    // 局部作用域

    public function scopeCate(Builder $query)
    {
        $query->where(['pid' => 0]);
    }

    // 关联

    public function withType()
    {
        return $this->hasMany(self::class, 'pid', 'id');
    }
}
