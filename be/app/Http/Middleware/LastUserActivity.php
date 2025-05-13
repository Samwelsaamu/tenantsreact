<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;

class LastUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $expiretime=Carbon::now()->addMinutes(1);
            Cache::put('this-user-is-online-'. Auth::user()->id,true,$expiretime);
            $useractivity = User::findOrFail(Auth::user()->id);
            $useractivity->current_activity_at=Carbon::now();
            $useractivity->save();
        }
        return $next($request);
    }
}
