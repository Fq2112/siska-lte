<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Support\Facades\Auth;

class SeekerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->isSeeker()) {
                return $next($request);
            }
        } else {
            return redirect()->guest(route('show.login.form'))
                ->with('expire', 'The page you requested requires authentication, please login to your account.');
        }
        return response(view('errors.403'), 403);
    }
}
