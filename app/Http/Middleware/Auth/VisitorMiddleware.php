<?php

namespace App\Http\Middleware\Auth;

use App\Models\Visitor;
use Closure;

class VisitorMiddleware
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
        Visitor::hit();

        return $next($request);
    }
}
