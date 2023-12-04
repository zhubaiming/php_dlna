<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $offset = 1;

    protected $limit = 10;

    protected function setOffset(int $offset)
    {
        $this->offset = $offset;
    }

    protected function setLimit(int $limit)
    {
        $this->limit = $limit;
    }

    protected function getPageArray()
    {
        return [$this->limit, ['*'], 'page', $this->offset];
    }
}
