<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaUrl extends Model
{
    use HasFactory;

    public $table='media_urls';

    public $timestamps = false;
}
