<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AppToken;

class VerifyAppKey
{
    public function handle(Request $request, Closure $next)
    {
        $appKey = $request->header('X-APP-KEY');

        if (!$appKey) {
            return response()->json(['message' => 'Missing X-APP-KEY'], 404);
        }

        $token = AppToken::where('app_key', $appKey)->first();

        if (!$token) {
            return response()->json(['message' => 'Invalid X-APP-KEY'], 401);
        }

        // Update last used
        $token->update(['last_used_at' => now()]);

        return $next($request);
    }
}

// class VerifyAppKey
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
//      * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
//      */
//     public function handle(Request $request, Closure $next)
//     {
//         return $next($request);
//     }
// }
