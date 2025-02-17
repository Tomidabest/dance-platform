<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!auth()->check())
        {
            return redirect('/')->with('error', 'Please log in first');
        }
        if (auth()->user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access');
        }
        $cookieName = 'personal_token_' . auth()->id();
        if (!$request->cookie($cookieName)) {
            Auth::logout();
            return redirect('/login')->with('error', 'Please log in again');
        }
        
        return $next($request);
    }
}
