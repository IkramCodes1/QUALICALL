<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class UpdateLastActivity
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);

            if ($accessToken) {
                // Vérifie l'inactivité (plus de 30 minutes)
                $lastActivity = $accessToken->last_activity_at ?? $accessToken->last_used_at;

                if ($lastActivity && Carbon::parse($lastActivity)->diffInMinutes(now()) > 35) {
                    $accessToken->delete(); // Supprime le token
                    return response()->json(['message' => __("session_expired")], 401);
                }

                $now = \Carbon\Carbon::now();

                if (!$accessToken->last_activity_at || \Carbon\Carbon::parse($accessToken->last_activity_at)->diffInMinutes($now) >= 5) {
                    $accessToken->forceFill(['last_activity_at' => $now])->save();
                }
            }
        }

        return $next($request);
    }
}
