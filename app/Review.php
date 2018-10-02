<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Review
 *
 * @property int $id
 * @property int $post_id
 * @property string|null $effective_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $voters
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Review onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Review withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Review withoutTrashed()
 * @mixin \Eloquent
 */
class Review extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    use SoftDeletes;

    protected $fillable = array(
        'post_id',

    );

    protected $dates = ['deleted_at'];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function voters()
    {
        return $this->belongsToMany('App\User', 'review_voting', 'review_id', 'user_id')->withTimestamps();
    }

    // CREATE SCOPES -----------------------------------------------

}
