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
        $event->user->save();

        DB::table('log_user_levels')->insert([
            'user_id' => $event->user->user_id,
            'level' => 0,
            'point' => 100,
            'point_total' => 100
        ]);

        DB::table('log_user_point_infos')->insert([
            'user_id' => $event->user->user_id,
            'point' => 100,
            'point_reasons' => '用户成功注册',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
