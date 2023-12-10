<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaEpisode extends Model
{
    use HasFactory;

    public $table = 'media_episode';

    public $timestamps = false;
}
