<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;



class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next , $role): Response
    {
     
    
     
       if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($role === 'admin' && !$user->isAdmin()) {
            return Inertia::render('Errors/403')->toResponse($request);
        }

        if ($role === 'vendor' && !$user->isVendor()) {
            return Inertia::render('Errors/403')->toResponse($request);
        }

        if ($role === 'customer' && !$user->isCustomer()) {
            return Inertia::render('Errors/403')->toResponse($request);
        }

        return $next($request);
    
    }
}


