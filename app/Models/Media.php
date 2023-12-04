<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    public function urls()
    {
        return $this->hasMany(MediaUrl::class, 'mediaId', 'id');
    }
}
