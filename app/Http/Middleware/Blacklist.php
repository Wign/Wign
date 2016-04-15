<?php namespace App\Http\Middleware;

use Closure;
use App\Blacklist as BL;

class Blacklist {

	protected $blip;

    public function __construct(BL $blip) {
		$this->blip = $blip;
    }

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		
		$myip = $request->ip();
		$blacklistes = $this->blip->get();

		foreach($blacklistes as $blacklisted) {
			if($blacklisted->ip == $myip & $request->path() != "blacklist") {
			//if($blacklisted->ip == $myip) {
				return redirect()->action('IndexController@blacklist')->with(['ip' => $blacklisted->ip, 'reason' => $blacklisted->reason]);
			}
		}
		
		return $next($request);
	}

}