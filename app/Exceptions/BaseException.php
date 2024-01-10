<?php

namespace App\Exceptions;

use App\Services\WorkWeixin\MarkdownMessage;
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

        (new MarkdownMessage())->sendException('email', 'benhai@icloudpdtzzs.wecom.work', static::class, $code, $message);
    }

    public function getPrimitives()
    {
        return $this->primitives;
    }
}
