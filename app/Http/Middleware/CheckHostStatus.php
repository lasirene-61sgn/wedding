<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Symfony\Component\HttpFoundation\Response;

class CheckHostStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::guard('host')->check() && Auth::guard('host')->user()->status === 'inactive'){
            if($request->ajax()){
                return response()->json(['Message' => 'Your account is inactuve'], 403);
            }

            Auth::guard('host')->logout();
            return redirect()->route('host.login')->with('error', 'Your Account Is Frozen');
        }
        return $next($request);
    }
}
