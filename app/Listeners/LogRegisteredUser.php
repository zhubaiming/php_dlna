<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class LogRegisteredUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        DB::table('user_level')->insert([
            'user_id' => $event->user->user_id,
            'level' => 0,
            'point' => 100
        ]);

        DB::table('log_user_point')->insert([
            'user_id' => $event->user->user_id,
            'point' => 100,
            'point_reasons' => '用户成功注册'
        ]);
    }
}
