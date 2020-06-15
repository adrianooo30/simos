<?php

namespace App\Http\Middleware;

use Closure;

class CustomMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $additional)
    {
        dump($additional);

        return $next($request);
    }

    public function terminate($request, $response)
    {
        dump($request);
    }
}
