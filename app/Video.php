<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Video
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Post $post
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Video onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereCameraUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video wherePlayings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereRecordedFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereSmallThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereVideoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereVideoUuid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Video withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video getOnlyTrashed()
 */
class Video extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'post_id',
        'video_uuid',
        'camera_uuid',
        'video_url',
        'thumbnail_url',
        'small_thumbnail_url',
        'playings'
    ];

    protected $dates = ['deleted_at'];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function post()
    {
        return $this->belongsTo('App\Post', 'post_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // CREATE SCOPES --------------------------------------------

    public function scopeGetOnlyTrashed()
    {
        return $this::onlyTrashed()->all();
    }
}
