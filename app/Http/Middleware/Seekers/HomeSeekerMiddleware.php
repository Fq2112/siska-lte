<?php

namespace App\Http\Middleware\Seekers;

use Closure;
use Illuminate\Support\Facades\Auth;

class HomeSeekerMiddleware
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
        if (Auth::check() || Auth::guard('admin')->check()) {
            return $next($request);
        }

        return redirect()->guest(route('show.login.form'))
            ->with('expire', 'The page you requested requires authentication, please login to your account.');
    }
}
