<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MaybeLaterMiddleware
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
        if ((int)$request->header('app_version') !== 6):
            return response()->json(['status' => 'fail', 'message' => 'Lütfen uygulamayı Google Play mağazasından güncelleyin.'],400);
        endif;
        return $next($request);
    }
}
