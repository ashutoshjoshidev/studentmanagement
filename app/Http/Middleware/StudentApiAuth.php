<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->hasRole('student')) {
            return response()->json(['error' => 'Unauthenticated user. Logged in with student account.'], 401);
        }
        return $next($request);
    }
}
