<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 15:32:09 +0200.
 */

namespace App\Models\Base;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property bool $admin
 * @property int $QCV
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $descriptions
 * @property \Illuminate\Database\Eloquent\Collection $likes
 * @property \Illuminate\Database\Eloquent\Collection $posts
 * @property \Illuminate\Database\Eloquent\Collection $remotion_votings
 * @property \Illuminate\Database\Eloquent\Collection $remotions
 * @property \Illuminate\Database\Eloquent\Collection $review_votings
 * @property \Illuminate\Database\Eloquent\Collection $videos
 * @property \Illuminate\Database\Eloquent\Collection $words
 *
 * @package App\Models\Base
 */
class User extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'admin' => 'bool',
		'QCV' => 'int'
	];

	public function descriptions()
	{
		return $this->hasMany(\App\Models\Description::class);
	}

	public function likes()
	{
		return $this->hasMany(\App\Models\Like::class);
	}

	public function posts()
	{
		return $this->hasMany(\App\Models\Post::class, 'creator_id');
	}

	public function remotion_votings()
	{
		return $this->hasMany(\App\Models\RemotionVoting::class);
	}

	public function remotions()
	{
		return $this->hasMany(\App\Models\Remotion::class);
	}

	public function review_votings()
	{
		return $this->hasMany(\App\Models\ReviewVoting::class);
	}

	public function videos()
	{
		return $this->hasMany(\App\Models\Video::class);
	}

	public function words()
	{
		return $this->hasMany(\App\Models\Word::class);
	}
}
