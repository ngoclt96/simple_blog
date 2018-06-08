<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\Query\Builder;

class CheckMemberMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  \Closure  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (request()->session()->get('member_id') == null) {
            return redirect('/login');
        }
        return $next($request);

    }
}
