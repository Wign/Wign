<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 14:31:28 +0200.
 */

namespace App\Models;

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
 * @package App\Models
 */
class Review extends Eloquent
{
    // MASS ASSIGNMENT ------------------------------------------
	protected $fillable = [
		'post_id',
		'effective_date'
	];

	// DEFINING RELATIONSHIPS -----------------------------------
	public function post()
	{
		return $this->belongsTo(\App\Models\Post::class);
	}

	public function review_votings()    //TODO
	{
		return $this->hasMany(\App\Models\ReviewVoting::class);
	}

    // CREATE SCOPES --------------------------------------------
}
