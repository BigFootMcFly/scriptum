<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestUri = request()->route()->uri;

        // guests already trying to login
        if (!auth()->check() && $requestUri === 'login') {
            return $next($request);
        }

        // guests not allowed, redirect to login
        if (!auth()->check() && $requestUri !== 'login') {
            return redirect()->route('filament.nomad.auth.login');
        }

        // logged in user is not an admin
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('unauthorised', 'You are unauthorised to access this page');
            //abort(403);
        }
        return $next($request);
    }
}
