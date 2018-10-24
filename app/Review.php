<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Review
 *
 * @property int $id
 * @property int $il_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Il $il
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $voters
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Review onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review post()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereIlId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Review withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Review withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review rank()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review postRank()
 */
class Review extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    use SoftDeletes;

    const BALLOTS_DIST_2 = [.6, .4];
    const BALLOTS_DIST_3 = [.5, .3, .2];
    const VOTE_WEIGHT = [0, 1, 2, 3, 5, 8]; // Fibonacci
    // const VOTE_WEIGHT = [0, 1, 2, 3, 4, 5]; // Linear
    // const VOTE_WEIGHT = [0, 1, 2, 4, 8, 16]; // Doubling
    const APPROVE_THRESHOLD = [.5, .6, .7, .8, .9];

    protected $fillable = array(
        'il_id',
        'user_id'   // Requestor
    );

    protected $dates = ['deleted_at'];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function voters()
    {
        return $this->belongsToMany('App\QCV', 'review_votings', 'review_id', 'qcv_id')->withTimestamps();
    }

    public function il()
    {
        return $this->belongsTo('App\Il', 'il_id');
    }

    public function user()  // Creator
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // CREATE SCOPES -----------------------------------------------

    public function scopePost()
    {
        return Il::find($this->il_id)->post()->get();
    }

    public function scopePostRank()
    {
        return $this->il()->first()->rank;
    }

}
