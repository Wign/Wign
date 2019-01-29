<?php

namespace App\Http\Middleware;

use Closure;

class Entry
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
        if (auth()->user()->qcv->rank == 0) {
            return redirect()->back()->with('message', __('text.no.access'));
        }

        return $next($request);
    }
}
