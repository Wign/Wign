<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Qcv
 *
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Qcv onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Qcv withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Qcv withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Remotion[] $remotions
 * @property int $id
 * @property int $user_id
 * @property int $rank
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Qcv whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Qcv whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Qcv whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Qcv whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Qcv whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Qcv whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Remotion[] $remotionVotings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviewVotings
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Qcv entitled()
 */
class Qcv extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    use SoftDeletes;

    protected $fillable = array(
        'user_id',
        'rank'
    );

    protected $dates = ['deleted_at'];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function remotions() // Target user
    {
        return $this->hasMany('App\Remotion', 'qcv_id');
    }

    public function remotionVotings()   //Voter
    {
        return $this->belongsToMany('App\Remotion', 'remotion_votings', 'qcv_id', 'remotion_id')->withTimestamps()->withPivot('approve');
    }

    public function reviewVotings() //Voter
    {
        return $this->belongsToMany('App\Review', 'review_votings', 'qcv_id', 'review_id')->withTimestamps()->withPivot('approve');
    }

    // CREATE SCOPES -----------------------------------------------

    public function scopeEntitled()
    {
        $this->has('user')->where('rank', '!=', 0);
    }

}
