<?php
namespace App\Http\Middleware;
use Closure;
class ValidProxies {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		// Proxies
		// The client ip is the ELB IP, trust it
		$request->setTrustedProxies([ $request->getClientIp() ]);
		return $next($request);
	}
}