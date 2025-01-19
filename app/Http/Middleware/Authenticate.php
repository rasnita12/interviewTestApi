<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function redirectTo(Request $request): Response
    {
        if (! $request->expectsJson()) {
            if(auth()->check()){
                if(auth()->user()->hasRole('Customer')){
                    return route('user.account');
                } else {
                    return route('dashboard');
                }
            } else {
                return route('login');
            }
        }
    }
}
