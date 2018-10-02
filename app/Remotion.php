<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
 */
class Remotion extends Model
{
    // MASS ASSIGNMENT ------------------------------------------

    protected $fillable = array(
        'user_id',
        'promotion'
    );

    protected $dates = ['effective_dates'];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function voters()
    {
        return $this->belongsToMany('App\User', 'remotion_voting', 'remotion_id', 'user_id')->withTimestamps();
    }

    // CREATE SCOPES -----------------------------------------------

}
