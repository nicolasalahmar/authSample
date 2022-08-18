<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Passport\Client as OClient;

class oauth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $oClient = OClient::where('password_client', 1)->first();

        $request['client_id'] =  $oClient->id;
        $request['client_secret'] = $oClient->secret;
        $request['scope'] = '*';
        return $next($request);
    }
}
