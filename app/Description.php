<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 14:31:17 +0200.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Description
 * 
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Post $post
 * @property \App\User $user
 * @property \Illuminate\Database\Eloquent\Collection $posts
 * @property \Illuminate\Database\Eloquent\Collection $taggables
 *
 * @package App\Models
 */
class Description extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $fillable = [
		'user_id',
		'post_id',
		'description'
	];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'taggables', 'description_id', 'tag_id')->withTimestamps();
    }

    public function post()
    {
        return $this->belongsTo('App\Post', 'post_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // CREATE SCOPES -----------------------------------------------
}
