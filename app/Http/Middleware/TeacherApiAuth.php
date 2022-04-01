<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TeacherApiAuth
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
        if (!$request->user()->hasRole('teacher')) {
            return response()->json(['error' => 'Unauthenticated user. Logged in with teacher account.'], 401);
        }
        return $next($request);
    }
}
