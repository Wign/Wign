<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 24 Sep 2018 14:37:49 +0200.
 */

namespace App\Models\Base;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Description
 * 
 * @property int $id
 * @property int $creator_id
 * @property int $post_id
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\User $user
 * @property \App\Models\Post $post
 * @property \Illuminate\Database\Eloquent\Collection $posts
 * @property \Illuminate\Database\Eloquent\Collection $taggables
 *
 * @package App\Models\Base
 */
class Description extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'creator_id' => 'int',
		'post_id' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'creator_id');
	}

	public function post()
	{
		return $this->belongsTo(\App\Models\Post::class);
	}

	public function posts()
	{
		return $this->hasMany(\App\Models\Post::class);
	}

	public function taggables()
	{
		return $this->hasMany(\App\Models\Taggable::class);
	}
}
