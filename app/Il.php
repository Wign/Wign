<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Il
 *
 * @property-read \App\Post $post
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Il onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Il withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Il withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviews
 * @property int $id
 * @property int $post_id
 * @property int $rank
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Il whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Il whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Il whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Il wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Il whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Il whereUpdatedAt($value)
 */
class Il extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    use SoftDeletes;

    protected $fillable = array(
        'post_id',
        'rank'
    );

    protected $dates = ['deleted_at'];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function post()
    {
        return $this->belongsTo('App\Post', 'post_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review', 'post_id');
    }

    // CREATE SCOPES -----------------------------------------------

}
