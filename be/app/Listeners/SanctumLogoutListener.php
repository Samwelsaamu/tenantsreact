<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserLogs;

class SanctumLogoutListener
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
    public function handle(Logout $event)
    {
        //
        $id=$event->user->id;
        $savelog = new UserLogs;
        $savelog->User =$id;
        $savelog->Message ='User Logged out.';
        $savelog->save();
        
        $event->user->tokens()->delete();
    }
}
