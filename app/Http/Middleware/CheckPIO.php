<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPIO
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in AND their role is 'PIO'
        if (auth()->check() && auth()->user()->role->name === 'PIO') {
            return $next($request); // Let them pass
        }

        // If they are a regular student, redirect them back to the dashboard with an error message
        return redirect()->route('dashboard')->with('error', 'Access Denied: Only PIOs can perform this action.');
    }
}