<?php

namespace App\Http\Middleware;

use App\Grant;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $data = User::find($request->user()->id);

        if($data->grants !== $guard)
            return redirect('/');

        return $next($request);
    }
}
