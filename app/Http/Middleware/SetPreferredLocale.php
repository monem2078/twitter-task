<?php

namespace App\Http\Middleware;

use Closure;

class SetPreferredLocale
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
        $locale = $request->header('Accept-Language');
        app()->setLocale($locale);
        return $next($request);
    }
}
