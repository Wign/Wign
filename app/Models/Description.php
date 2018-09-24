<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 14:31:17 +0200.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

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
 * @property \App\Models\Post $post
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $posts
 * @property \Illuminate\Database\Eloquent\Collection $taggables
 *
 * @package App\Models
 */
class Description extends Eloquent
{
    // MASS ASSIGNMENT ------------------------------------------
	protected $fillable = [
		'user_id',
		'post_id',
		'description'
	];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function tags()
    {
        return $this->belongsToMany('App\Model\Tag', 'taggables', 'description_id', 'tag_id')->withTimestamps();
    }

    // CREATE SCOPES -----------------------------------------------
}
