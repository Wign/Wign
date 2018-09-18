<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 15:34:09 +0200.
 */

namespace App\Models\Base;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Review
 * 
 * @property int $id
 * @property int $post_id
 * @property \Carbon\Carbon $effective_date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Post $post
 * @property \Illuminate\Database\Eloquent\Collection $review_votings
 *
 * @package App\Models\Base
 */
class Review extends Eloquent
{
	protected $casts = [
		'post_id' => 'int'
	];

	protected $dates = [
		'effective_date'
	];

	public function post()
	{
		return $this->belongsTo(\App\Models\Post::class);
	}

	public function review_votings()
	{
		return $this->hasMany(\App\Models\ReviewVoting::class);
	}
}
