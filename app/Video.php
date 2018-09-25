<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 18 Sep 2018 14:31:10 +0200.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Video
 * 
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property string $video_uuid
 * @property string $camera_uuid
 * @property string $recorded_from
 * @property string $video_url
 * @property string $thumbnail_url
 * @property string $small_thumbnail_url
 * @property int $playings
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Post $post
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $posts
 *
 * @package App\Models
 */
class Video extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $fillable = [
		'user_id',
		'post_id',
		'video_uuid',
		'camera_uuid',
		'recorded_from',
		'video_url',
		'thumbnail_url',
		'small_thumbnail_url',
		'playings'
	];

    // DEFINING RELATIONSHIPS -----------------------------------
	public function post()
	{
		return $this->belongsTo('App\Post', 'post_id');
	}

	public function user()
	{
		return $this->belongsTo('App\User', 'post_id');
	}

    // CREATE SCOPES --------------------------------------------
    /**
     *
     */
    public function scopeCountVideos($query)
    {

    }

    /**
     * Count the number og votes assigned to $signID
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $signID the id of the sign
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeCountVotes( $query, $signID ) {    //TODO
        return $query->where( 'sign_id', $signID )->count();
    }
}
