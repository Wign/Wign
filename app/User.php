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
 * @property int $blacklisted
 * @property string|null $reason
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Description[] $descriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $likes
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $posts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Qcv[] $qcvs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Remotion[] $remotionAuthor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Remotion[] $remotionVotings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $requestWords
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviewAuthor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviewVotings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[] $videos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $words
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User qcv()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBlacklisted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\User withoutTrashed()
 * @mixin \Eloquent
 * @property string $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User isAdmin()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereType($value)
 * @property string|null $ban_reason
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBanReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User postRank()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User ranks()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User userRank()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User rank()
 */
class User extends Authenticatable {
    // MASS ASSIGNMENT ------------------------------------------
	use Notifiable;
    use SoftDeletes;

    const ADMIN_TYPE = 'admin';
    const DEFAULT_TYPE = 'default';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
        'email',
        'password',
        'ban_reason',
        'type',
        // inactive / passive state to exclude from the votings
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
        return $this->belongsToMany('App\Post', 'likes', 'user_id', 'post_id')->withTimestamps();
    }

    public function remotionAuthor()    // Creator of this remotion
    {
        return $this->hasMany('App\Remotion', 'user_id');
    }

    public function reviewAuthor()
    {
        return $this->hasMany('App\Review', 'user_id');
    }

    public function requestWords()
    {
        return $this->belongsToMany('App\Word', 'request_words', 'user_id', 'word_id')->withTimestamps();
    }

    public function qcvs()
    {
        return $this->hasMany('App\Qcv', 'user_id');
    }

    // CREATE SCOPES -----------------------------------------------
    //TODO: bool pending remotion

    public function scopeIsAdmin()
    {
        return $this->type === self::ADMIN_TYPE;
    }

    public function scopeQcv()
    {
        return $this->qcvs()->first();
    }

    public function scopeRank()
    {
        return $this->qcv()->rank;
    }

    public function scopeRanks($value)
    {
        return User::qcvs()->where('rank', $value);
    }

}
