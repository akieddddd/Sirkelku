<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeenActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Update only if more than 2 minutes have passed to avoid too many DB queries
            if (!$user->last_seen_at || $user->last_seen_at->diffInMinutes(now()) >= 2) {
                // Perform a raw DB update to prevent updating the 'updated_at' column unnecessarily
                \DB::table('users')
                    ->where('id', $user->id)
                    ->update(['last_seen_at' => now()]);
            }
        }
        
        return $next($request);
    }
}
