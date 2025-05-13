<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserLogs;

class SanctumLoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // if (Auth::check()) {
        //     $expiretime=Carbon::now()->addMinutes(1);
        //     Cache::put('this-user-is-online-'. Auth::user()->id,true,$expiretime);
        //     $useractivity = User::findOrFail(Auth::user()->id);
        //     $useractivity->current_activity_at=Carbon::now();
        //     $useractivity->save();
        // }

        $id=$event->user->id;
        $savelog = new UserLogs;
        $savelog->User =$id;
        $savelog->Message ='User Logged in.';
        $savelog->save();
        
        
         //
        // $event->user->update([
        //     'last_login_at' => now(),
        //     'last_login_ip' => request()->ip(),
        // ]);

        // $event->user->last_login_at=$event->user->current_login_at ? $event->user->current_login_at :Carbon::now();
        // $event->user->current_login_at=Carbon::now();
        // $event->user->last_login_ip=request()->ip();
        // $event->user->save();

        // $token = $event->user->createToken('auth:api')->plainTextToken;
    }
}
