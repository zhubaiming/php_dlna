<?php

namespace App\Services\ShortMessage;

use Illuminate\Contracts\Foundation\Application;

class ShortMessage
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function guard(string $name)
    {
        return $this->app->make($name);
//        return $this->app->makeWith($name, ['id' => 1]);
    }
}
