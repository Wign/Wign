<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 14:31:17 +0200.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Description
 * 
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Post $post
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $posts
 * @property \Illuminate\Database\Eloquent\Collection $taggables
 *
 * @package App\Models
 */
class Description extends Eloquent
{
    // MASS ASSIGNMENT ------------------------------------------
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'user_id' => 'int',
		'post_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'post_id',
		'description'
	];

    // DEFINING RELATIONSHIPS -----------------------------------
	public function post()
	{
		return $this->belongsTo(\App\Models\Post::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function posts()
	{
		return $this->hasMany(\App\Models\Post::class);
	}

	public function taggables() //TODO
	{
		return $this->hasMany(\App\Models\Taggable::class);
	}

    // CREATE SCOPES -----------------------------------------------
}
