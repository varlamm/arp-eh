<?php

namespace Crater\Http\Middleware;

use Closure;
use Crater\Models\Setting;

class RedirectIfInstalled
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
       
        if (Setting::getSetting('profile_complete') === 'COMPLETED') {
            return redirect('login');
        }
        

        return $next($request);
    }
}
