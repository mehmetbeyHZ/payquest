<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SuspendMiddleware
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

        if (Auth::user()->is_banned === 1):
            return response()->json(['status' => 'fail', 'message' => 'Hesabınız askıya alındı. Bunun bir hata olduğunu düşünüyorsan support@payquestion.com'],400);
        endif;
        return $next($request);
    }
}
