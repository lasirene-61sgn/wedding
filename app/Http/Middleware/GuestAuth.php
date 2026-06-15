<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
{
    if (!session()->has('guest_phone')) {
        return redirect()->route('guest.login')->with('error', 'Please login first.');
    }

    return $next($request); // 100% vital to let the request reach selectWedding()
}
}
