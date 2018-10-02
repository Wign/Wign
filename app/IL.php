<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\IL
 *
 * @property-read \App\Post $post
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\IL onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\IL withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\IL withoutTrashed()
 * @mixin \Eloquent
 */
class IL extends Model
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

    // CREATE SCOPES -----------------------------------------------

}
