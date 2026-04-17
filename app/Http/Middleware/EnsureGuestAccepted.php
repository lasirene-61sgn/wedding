<?php

namespace App\Http\Middleware;

use App\Models\GuestList;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuestAccepted
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $inviteId = $request->route('id');
        $phone = session('guest_phone');
        $invite = GuestList::where('id', $inviteId)->where('guest_number', $phone)->first();

        if(!$invite || $invite->status !== 'accepted'){
            return redirect()->route('guest.login')->with('error', 'Please Accept the invite First');
        }
        return $next($request);
    }
}
