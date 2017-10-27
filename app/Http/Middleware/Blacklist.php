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
		$blacklist = $this->blip->where('ip', $myip)->first();

		if($blacklist != null & $request->path() != "blacklist") {
			return redirect()->action('IndexController@blacklist')->with( array( 'ip' => $blacklist->ip, 'reason' => $blacklist->reason ) );
		}
		return $next($request);
	}

}