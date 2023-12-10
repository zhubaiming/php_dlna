<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AncientScope implements Scope
{

    /**
     * 将作用域应用于给定的 Eloquent 查询构建器
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy('updated_at', 'desc');
    }
}
