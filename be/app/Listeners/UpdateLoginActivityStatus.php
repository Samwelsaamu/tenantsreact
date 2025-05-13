<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Property;
use Carbon\Carbon;
class UpdateLoginActivityStatus
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->user->last_login_at=$event->user->current_login_at ? $event->user->current_login_at :Carbon::now();
        $event->user->current_login_at=Carbon::now();
        $event->user->save();
        Property::setUserLogs('Log In');
    }
}
