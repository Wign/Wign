<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Sep 2018 16:59:55 +0200.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Like
 * 
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Post $post
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Like extends Eloquent
{
	protected $casts = [
		'post_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'post_id',
		'user_id'
	];

	public function post()
	{
		return $this->belongsTo(\App\Models\Post::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
