<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'code' => $this->getCode(),
            'message' => $this->getMessage()
        ], $this->getCode());
    }
}
