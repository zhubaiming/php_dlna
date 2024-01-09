<?php

namespace App\Jobs;

use App\Services\ShortMessage\SuperModuleInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class SendSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $module;

    protected $credentials;

    /**
     * 创建一个新的任务实例
     */
    public function __construct(SuperModuleInterface $module, array $credentials)
    {
        $this->module = $module;

        $this->credentials = $credentials;

        $this->onQueue('send_login_short_message');

        $this->onConnection('redis');
    }

    /**
     * 运行任务
     */
    public function handle(): void
    {
        $this->module->send(...$this->credentials);

        Redis::Setex('weixin_login_sms_' . $this->credentials[0] . '_' . $this->credentials[1], 300, $this->credentials[2]);
    }

    public function failed()
    {

    }
}
