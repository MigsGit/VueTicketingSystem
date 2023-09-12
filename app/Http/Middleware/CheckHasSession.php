<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckHasSession
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
        if(!$request->session()->exists('id')){
            // session()->get()
            // return redirect('/Laravel10System/Dashboard');
            return redirect('/');
            // return redirect('/panel_template/dashboard');

        }
        return $next($request);
    }
}