<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class SetUserCookie
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
        if(isset($_GET['utm_source'])) Cookie::queue(Cookie::make('utm_source', $_GET['utm_source'], 60*24*30));
        if(isset($_GET['utm_medium'])) Cookie::queue(Cookie::make('utm_medium', $_GET['utm_medium'], 60*24*30));
        if(isset($_GET['utm_campaign'])) Cookie::queue(Cookie::make('utm_campaign', $_GET['utm_campaign'], 60*24*30));

        return $next($request);
    }
}
