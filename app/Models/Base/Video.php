<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 15:34:24 +0200.
 */

namespace App\Models\Base;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Video
 * 
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property string $video_uuid
 * @property string $camera_uuid
 * @property string $recorded_from
 * @property string $video_url
 * @property string $thumbnail_url
 * @property string $small_thumbnail_url
 * @property int $playings
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Post $post
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $posts
 *
 * @package App\Models\Base
 */
class Video extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'user_id' => 'int',
		'post_id' => 'int',
		'playings' => 'int'
	];

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
}
