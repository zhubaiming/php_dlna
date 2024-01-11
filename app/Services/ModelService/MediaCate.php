<?php

namespace App\Services\ModelService;

use App\Models\MediaCate as Model;

class MediaCate
{
    public function getVideoAllNotPage()
    {
        return Model::video()->parent()->with(['withManyChild'])->get();
    }

    public function getAllNotPage()
    {

    }
}
