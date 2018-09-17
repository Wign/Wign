<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Sep 2018 16:59:55 +0200.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Language
 * 
 * @property int $id
 * @property string $language
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $posts
 * @property \Illuminate\Database\Eloquent\Collection $words
 *
 * @package App\Models
 */
class Language extends Eloquent
{
	protected $fillable = [
		'language'
	];

	public function posts()
	{
		return $this->hasMany(\App\Models\Post::class);
	}

	public function words()
	{
		return $this->hasMany(\App\Models\Word::class);
	}
}
