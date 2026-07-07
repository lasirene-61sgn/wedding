<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiGuestAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $phone = $request->header('X-Guest-Phone') ?? $request->bearerToken();
        
        if (!$phone) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Guest phone required.'], 401);
        }
        
        return $next($request);
    }
}
