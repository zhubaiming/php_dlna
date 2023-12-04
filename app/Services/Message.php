<?php

namespace App\Services;

//use App\Services\Aliyun\SMS;

class Message
{
    private $service;

    public function __construct(string $service)
    {
        $this->service = $service;
    }

    public function sendMsg($method_name, $params)
    {
        switch ($this->service) {
            case 'aliyun':
                $className = 'App\Services\Aliyun\SMS';
                break;
        }

        $class = new $className;
        $class->$method_name(...$params);
    }
}
