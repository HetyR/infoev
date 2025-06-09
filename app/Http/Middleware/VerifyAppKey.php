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
            abort(404);
        }

        $token = AppToken::where('app_key', $appKey)->first();

        if (!$token) {
            return response()->json(['message' => 'Invalid X-APP-KEY'], 401);
        }

        // (Optional) cek expired token
        if ($token->created_at->lt(now()->subDays(7))) {
            return response()->json(['message' => 'Expired X-APP-KEY'], 401);
        }

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
