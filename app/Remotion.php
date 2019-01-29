<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Remotion
 *
 * @property int $id
 * @property int $qcv_id
 * @property int $user_id
 * @property int $promotion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Qcv $qcv
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Qcv[] $voters
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Remotion onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion userRank()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion wherePromotion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereQcvId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Remotion withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Remotion withoutTrashed()
 * @mixin \Eloquent
 * @property int $decided
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereDecided($value)
 */
class Remotion extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    use SoftDeletes;

    const PROMOTE_THRESHOLD =   [.5, .6, .7, .8, .9];
    //const DEMOTE_THRESHOLD =    [.9, .8, .7, .6, .5];
    const DEMOTE_THRESHOLD =  [.5, .5, .5, .5, .5];

    protected $fillable = array(
        'qcv_id',
        'user_id',  // Requestor
        'promotion',
        'decided'
    );

    protected $dates = ['effective_dates']; //TODO Follow up if this is necessary

    // DEFINING RELATIONSHIPS -----------------------------------
    public function voters()
    {
        return $this->belongsToMany('App\Qcv', 'remotion_votings', 'remotion_id', 'qcv_id')->withTimestamps()->withPivot('approve');
    }

    public function qcv()   //Target user
    {
        return $this->belongsTo('App\Qcv', 'qcv_id');
    }

    public function user()  //Author
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // CREATE SCOPES -----------------------------------------------
    //TODO count num assigned votes
    //TODO count num approved votes
    public function scopeUserRank()
    {
        return $this->qcv()->first()->rank;
    }
}
