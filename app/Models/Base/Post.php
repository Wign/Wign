<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 15:16:20 +0200.
 */

namespace App\Models\Base;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Post
 * 
 * @property int $id
 * @property int $creator_id
 * @property int $word_id
 * @property int $video_id
 * @property int $description_id
 * @property int $language_id
 * @property int $il
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\User $user
 * @property \App\Models\Description $description
 * @property \App\Models\Language $language
 * @property \App\Models\Video $video
 * @property \App\Models\Word $word
 * @property \Illuminate\Database\Eloquent\Collection $descriptions
 * @property \Illuminate\Database\Eloquent\Collection $likes
 * @property \Illuminate\Database\Eloquent\Collection $reviews
 * @property \Illuminate\Database\Eloquent\Collection $videos
 *
 * @package App\Models\Base
 */
class Post extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'creator_id' => 'int',
		'word_id' => 'int',
		'video_id' => 'int',
		'description_id' => 'int',
		'language_id' => 'int',
		'il' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'creator_id');
	}

	public function description()
	{
		return $this->belongsTo(\App\Models\Description::class);
	}

	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class);
	}

	public function video()
	{
		return $this->belongsTo(\App\Models\Video::class);
	}

	public function word()
	{
		return $this->belongsTo(\App\Models\Word::class);
	}

	public function descriptions()
	{
		return $this->hasMany(\App\Models\Description::class);
	}

	public function likes()
	{
		return $this->hasMany(\App\Models\Like::class);
	}

	public function reviews()
	{
		return $this->hasMany(\App\Models\Review::class);
	}

	public function videos()
	{
		return $this->hasMany(\App\Models\Video::class);
	}
}
