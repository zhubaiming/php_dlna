<?php

namespace App\Console\Commands;

use App\Services\Reptile\Olevod;
use Illuminate\Console\Command;

class ReptileVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reptile:video
     {resource : 数据来源网站}
     {type : 视频类型}
     {year : 视频年份}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '爬取视频video';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $resource = $this->argument('resource');

        switch ($resource) {
            case '欧乐影院':
                $class = new Olevod();
                break;
        }

        $class->beginRep($this->argument('type'), $this->argument('year'));
    }
}
