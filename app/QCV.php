<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\QCV
 *
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\QCV onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\QCV withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\QCV withoutTrashed()
 * @mixin \Eloquent
 */
class QCV extends Model
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

    // CREATE SCOPES -----------------------------------------------

}
