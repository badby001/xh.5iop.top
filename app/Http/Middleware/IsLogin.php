<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class IsLogin
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
        if (_admCode()) {
            Redis::set('admOnline:' . _admCode(), getTime(0));//记录在线账号
            Redis::expire('admOnline:' . _admCode(), base()['redisTime']);//设置在线时间,用于做用户统计
            return $next($request);
        } else {
            return redirect('sys/login');
        }
    }
}
