<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $admin
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\QCV[] $QCVs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Description[] $descriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $likes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $posts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Remotion[] $remotionVotings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Remotion[] $remotions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RequestWord[] $requestWords
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviewVotings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[] $videos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $words
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\User onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\User withoutTrashed()
 * @property int $blacklisted
 * @property string $reason
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBlacklisted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereReason($value)
 */
class User extends Authenticatable {
    // MASS ASSIGNMENT ------------------------------------------
	use Notifiable;
    use SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
        'email',
        'password',
        'admin',
        'blacklisted',
        'reason'
	];
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
        'remember_token',
	];

    protected $dates = ['deleted_at'];

    // DEFINING RELATIONSHIPS -----------------------------------
    //TODO: bool pending remotion

    public function words()
    {
        return $this->hasMany('App\Word', 'user_id');
    }

    public function videos()
    {
        return $this->hasMany('App\Video', 'user_id');
    }

    public function descriptions()
    {
        return $this->hasMany('App\Description', 'user_id');
    }

    public function posts()
    {
        return $this->hasMany('App\Post', 'user_id');
    }

    public function likes()
    {
        return $this->belongsToMany('App\Post', 'likes', 'user_id', 'post_id');
    }

    public function remotionVotings()
    {
        return $this->belongsToMany('App\Remotion', 'remotion_votings', 'user_id', 'remotion_id');
    }

    public function remotions()
    {
        return $this->hasMany('App\Remotion', 'user_id');
    }

    public function reviewVotings()
    {
        return $this->belongsToMany('App\Review', 'review_votings', 'user_id', 'review_id');
    }

    public function requestWords()
    {
        return $this->belongsToMany('App\Word', 'request_words', 'user_id', 'word_id');
    }

    public function QCVs()
    {
        return $this->hasMany('App\QCV', 'user_id');
    }

    // CREATE SCOPES -----------------------------------------------

}
