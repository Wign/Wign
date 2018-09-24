<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 24 Sep 2018 14:33:59 +0200.
 */

namespace App\Models\Base;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class IL
 * 
 * @property int $id
 * @property int $post_id
 * @property int $rank
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Post $post
 * @property \Illuminate\Database\Eloquent\Collection $posts
 *
 * @package App\Models\Base
 */
class IL extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'ILs';

	protected $casts = [
		'post_id' => 'int',
		'rank' => 'int'
	];

	public function post()
	{
		return $this->belongsTo(\App\Models\Post::class);
	}

	public function posts()
	{
		return $this->hasMany(\App\Models\Post::class);
	}
}
