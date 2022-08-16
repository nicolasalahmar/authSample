<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if(! $request->expectsJson())
                    return redirect(RouteServiceProvider::HOME);
                else
                    return response()->json(['message'=>'Already logged in.']);
            }
        }

        return $next($request);
    }
}
