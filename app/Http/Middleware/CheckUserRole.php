<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */    

    public function handle($request, Closure $next)
    {
        if(Auth::check())
        {
            $prefix = $request->route()->getPrefix();
            if($prefix == '/admin')
            {
                if(Auth::user()->isUser())
                {
                    return redirect()->route('user.dashboard');
                }
            }else if($prefix == '/user')
            {
                if(Auth::user()->isAdmin())
                {
                    return redirect()->route('admin.dashboard');
                }
            }
        }

        return $next($request);
    }
}
