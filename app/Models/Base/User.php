<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 24 Sep 2018 14:35:13 +0200.
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
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $q_c_v_s
 * @property \Illuminate\Database\Eloquent\Collection $blacklists
 * @property \Illuminate\Database\Eloquent\Collection $descriptions
 * @property \Illuminate\Database\Eloquent\Collection $likes
 * @property \Illuminate\Database\Eloquent\Collection $posts
 * @property \Illuminate\Database\Eloquent\Collection $remotion_votings
 * @property \Illuminate\Database\Eloquent\Collection $remotions
 * @property \Illuminate\Database\Eloquent\Collection $request_words
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
		'admin' => 'bool'
	];

	public function qcvs()
	{
		return $this->hasMany(\App\Models\QCV::class);
	}

	public function blacklists()
	{
		return $this->hasMany(\App\Models\Blacklist::class);
	}

	public function descriptions()
	{
		return $this->hasMany(\App\Models\Description::class, 'creator_id');
	}

	public function likes()
	{
		return $this->hasMany(\App\Models\Like::class);
	}

	public function posts()
	{
		return $this->hasMany(\App\Models\Post::class, 'author_id');
	}

	public function remotion_votings()
	{
		return $this->hasMany(\App\Models\RemotionVoting::class, 'voter_id');
	}

	public function remotions()
	{
		return $this->hasMany(\App\Models\Remotion::class);
	}

	public function request_words()
	{
		return $this->hasMany(\App\Models\RequestWord::class);
	}

	public function review_votings()
	{
		return $this->hasMany(\App\Models\ReviewVoting::class, 'voter_id');
	}

	public function videos()
	{
		return $this->hasMany(\App\Models\Video::class, 'creator_id');
	}

	public function words()
	{
		return $this->hasMany(\App\Models\Word::class, 'creator_id');
	}
}
