<?php

namespace App\Services\ShortMessage;

interface SuperModuleInterface
{
    public function send(string $prefix, string $phone, string $message);
}
