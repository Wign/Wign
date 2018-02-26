<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

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
		$request->setTrustedProxies([ $request->getClientIp() ], Request::HEADER_X_FORWARDED_AWS_ELB);
		return $next($request);
	}
}