<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Word;


class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		view()->share([
			'signCount' => Word::has('signs')->count(),
			'wignVersion' => '0.7 (beta)',
			'fbURL' => 'https://www.facebook.com/wign.dk/',
			'gitURL' => 'https://github.com/Thanerik/Wign',
			'email' => 'troels@t-troels.dk',
			'appID' => 'a-49088bd0-39cc-0132-ccc4-12313914f10b'
			]);
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);
	}

}
