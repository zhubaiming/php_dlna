<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    protected $table = 'music';

    // 访问器
    protected function artists(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => explode('/', $value)
        );
    }
}
