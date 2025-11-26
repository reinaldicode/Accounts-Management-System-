<?php
// app/Http/Middleware/CheckAMSSession.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAMSSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('ams_user')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        return $next($request);
    }
}