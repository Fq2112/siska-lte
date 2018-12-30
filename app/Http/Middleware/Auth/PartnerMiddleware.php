<?php

namespace App\Http\Middleware\Auth;

use Closure;

class PartnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('SISKA_API_KEY') == $request->key && env('SISKA_API_SECRET') == $request->secret) {
            return $next($request);

        } else {
            return response()->json([
                'status' => "403 ERROR",
                'success' => false,
                'message' => 'Forbidden Access! Your client does not have permission to get URL /adsense from this server.'
            ], 403);
        }
    }
}
