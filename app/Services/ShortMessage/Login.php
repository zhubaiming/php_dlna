<?php

namespace App\Services\ShortMessage;

use App\Services\WorkWeixin\TextMessage;

class Login implements SuperModuleInterface
{
    public function send(string $prefix, string $phone, string $message): void
    {
        $services = $this->getServices();

        foreach ($services as $service) {
            $service->sendLoginCode($prefix, $phone, ['code' => $message]);
        }
    }

    private function getServices(): array
    {
        return [
            new \App\Services\Aliyun\ShortMessage('dysmsapi.aliyuncs.com'),
            new TextMessage()
        ];
    }
}
