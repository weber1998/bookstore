<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsLibrarian
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
       //bejelentkezett felhasználó: Auth::user()
       //0: admin, 1: könyvtáros, 2: felhasználó
        if (Auth::user() && Auth::user()->permission == 1) { 
            return $next($request); 
        } 
        return redirect('dashboard')->with('error','You have not admin access');
    }
}
