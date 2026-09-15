<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetUserLocale
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user && !empty($user->langue)) {
            App::setLocale($user->langue);
        } else {
            App::setLocale('en');
        }
        return $next($request);
    }
}
