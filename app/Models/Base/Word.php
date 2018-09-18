<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 15:34:31 +0200.
 */

namespace App\Models\Base;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Word
 * 
 * @property int $id
 * @property int $language_id
 * @property int $user_id
 * @property string $word
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Language $language
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $aliases
 * @property \Illuminate\Database\Eloquent\Collection $posts
 *
 * @package App\Models\Base
 */
class Word extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'language_id' => 'int',
		'user_id' => 'int'
	];

	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function aliases()
	{
		return $this->hasMany(\App\Models\Alias::class, 'parent_word_id');
	}

	public function posts()
	{
		return $this->hasMany(\App\Models\Post::class);
	}
}
