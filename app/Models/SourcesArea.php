<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourcesArea extends Model
{
    use HasFactory;

    public $table = 'sources_area';

    // 匿名全局作用域

    protected static function booted()
    {
        static::addGlobalScope('sotr', function (Builder $builder) {
            $builder->orderBy('sort', 'asc');
        });
    }
}
