<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class ApiVerifySign
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $t = checkToken($request['key'], $request['token']);//验证token
        if (objectToArray($t)['original']['code'] == 0) {
            return $t;
        } else {
            return $next($request);
        }
    }
}
