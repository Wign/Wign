<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 15:23:23 +0200.
 */

namespace App\Models\Base;

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
 * @package App\Models\Base
 */
class Language extends Eloquent
{
	public function posts()
	{
		return $this->hasMany(\App\Models\Post::class);
	}

	public function words()
	{
		return $this->hasMany(\App\Models\Word::class);
	}
}
