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
 * @property int $new_post_il_id
 * @property int $old_post_il_id
 * @property-read \App\Il $newIl
 * @property-read \App\Il $oldIl
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereNewPostIlId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereOldPostIlId($value)
 * @property int $decided
 * @property-read mixed $score
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review fetchOld()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereDecided($value)
 */
class Review extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    use SoftDeletes;

    protected $fillable = array(
        'new_post_il_id',
        'old_post_il_id',
        'user_id',   // Requestor
        'decided'
    );

    protected $dates = ['deleted_at'];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function voters()
    {
        return $this->belongsToMany('App\QCV', 'review_votings', 'review_id', 'qcv_id')->withTimestamps()->withPivot('approve');
    }

    public function newIl()
    {
        return $this->belongsTo('App\Il', 'new_post_il_id');
    }

    public function oldIl()
    {
        return $this->belongsTo('App\Il', 'old_post_il_id');
    }

    public function user()  // Creator
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // CREATE SCOPES -----------------------------------------------

    public function getScoreAttribute($value)
    {

        return ucfirst($value);
    }

    public function scopePost()
    {
        return Il::find($this->new_post_il_id)->post()->get();
    }

    public function scopePostRank()
    {
        return $this->newIl()->first()->rank;
    }

    public function scopeFetchOld()  {
        return $this->oldIl()->withTrashed()->first();
    }

}
