<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Video
 *
 * @property int $id
 * @property int $user_id
 * @property string $video_uuid
 * @property string $camera_uuid
 * @property string $video_url
 * @property string $thumbnail_url
 * @property string $small_thumbnail_url
 * @property int $playings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $posts
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereCameraUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video wherePlayings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereSmallThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereVideoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereVideoUuid($value)
 * @mixin \Eloquent
 */
class Video extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    protected $fillable = [
        'user_id',
        'video_uuid',
        'camera_uuid',
        'video_url',
        'thumbnail_url',
        'small_thumbnail_url',
        'playings'
    ];


    // DEFINING RELATIONSHIPS -----------------------------------
    public function posts()
    {
        return $this->hasMany('App\Post', 'video_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // CREATE SCOPES --------------------------------------------
}
