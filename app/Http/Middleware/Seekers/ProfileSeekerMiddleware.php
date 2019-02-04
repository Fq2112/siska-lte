<?php

namespace App\Http\Middleware\Seekers;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class ProfileSeekerMiddleware
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
        $findUser = $request->id;
        if (Auth::check()) {
            $user = User::find(Auth::user()->id);
            if ($user->id == $findUser) {
                return $next($request);
            }

        } elseif (Auth::guard('admin')->check()) {
            return $next($request);

        } else {
            return redirect()->guest(route('show.login.form'))
                ->with('expire', 'The page you requested requires authentication, please login to your account.');
        }
        return response()->view('errors.403');
    }
}
