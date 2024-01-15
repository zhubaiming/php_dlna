<?php

namespace App\Services\WorkWeixin;

use Illuminate\Contracts\Foundation\Application;

class WorkWeixin
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function channel(string $name)
    {
        return $this->app->make($name);
    }
}
