<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 14:29:09 +0200.
 */

namespace App\Models;

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
 * @property \Illuminate\Database\Eloquent\Collection $descriptions
 * @property \Illuminate\Database\Eloquent\Collection $likes
 * @property \Illuminate\Database\Eloquent\Collection $posts
 * @property \Illuminate\Database\Eloquent\Collection $remotion_votings
 * @property \Illuminate\Database\Eloquent\Collection $remotions
 * @property \Illuminate\Database\Eloquent\Collection $review_votings
 * @property \Illuminate\Database\Eloquent\Collection $videos
 * @property \Illuminate\Database\Eloquent\Collection $words
 *
 * @package App\Models
 */
class User extends Eloquent
{
    // MASS ASSIGNMENT ------------------------------------------
	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'password',
		'admin',
		'remember_token'
	];

    // DEFINING RELATIONSHIPS -----------------------------------

    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }

    public function words()
    {
        return $this->hasMany(\App\Models\Word::class);
    }

    public function videos()
    {
        return $this->hasMany(\App\Models\Video::class);
    }

    public function descriptions()
    {
        return $this->hasMany(\App\Models\Description::class);
    }
	// CREATE SCOPES --------------------------------------------


    /**
     * Scopes down to valid posts WITH video
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeWithVideo( $query ) {
        return $query->has( 'videos' );
    }
}
