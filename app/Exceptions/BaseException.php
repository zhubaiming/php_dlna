<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    protected array $primitives;

    public function __construct(array $primitives, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = $message;

        $this->code = $code;

        $this->primitives = $primitives;
    }

    public function getPrimitives()
    {
        return $this->primitives;
    }
}
