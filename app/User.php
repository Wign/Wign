<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

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
class User extends Model
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
        return $this->hasMany('App\Post');
    }

    public function words()
    {
        return $this->hasMany('App\Word');
    }

    public function videos()
    {
        return $this->hasMany('App\Video');
    }

    public function descriptions()
    {
        return $this->hasMany('App\Description');
    }

    public function likes()
    {
        return $this->belongsToMany('App\Post', 'likes', 'user_id', 'post_id')->withTimestamps();
    }

    public function QCVs()
    {
        return $this->hasMany('App\QCV', 'user_id');
    }

	// CREATE SCOPES --------------------------------------------

    public function QCV()
    {
        return $this->QCVs()->whereNotNull('delete_at')->first('rank');
    }
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
