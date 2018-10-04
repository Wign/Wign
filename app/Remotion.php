<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Remotion
 *
 * @property int $id
 * @property int $user_id
 * @property int $promotion
 * @property string|null $effective_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $voters
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion wherePromotion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereDeletedAt($value)
 * @property int $QCV_id
 * @property-read \App\QCV $QCV
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Remotion onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereQCVId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Remotion withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Remotion withoutTrashed()
 */
class Remotion extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    use SoftDeletes;

    protected $fillable = array(
        'QCV_id',
        'promotion'
    );

    protected $dates = ['effective_dates'];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function voters()
    {
        return $this->belongsToMany('App\User', 'remotion_voting', 'remotion_id', 'user_id')->withTimestamps();
    }

    public function QCV()
    {
        return $this->belongsTo('App\QCV', 'QCV_id');
    }

    // CREATE SCOPES -----------------------------------------------
    //TODO count num assigned votes
    //TODO count num approved votes
}
