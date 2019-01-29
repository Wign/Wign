<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Post
 *
 * @property int $id
 * @property int $user_id
 * @property int $word_id
 * @property int $video_id
 * @property int $description_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\User $creator
 * @property-read \App\Description $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Il[] $ils
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $likes
 * @property-read \App\Video $video
 * @property-read \App\Word $word
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post countLikes()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deactiveReviews()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post il()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post inReview()
 * @method static \Illuminate\Database\Query\Builder|\App\Post onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post rank()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereDescriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereWordId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Post withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Post withoutTrashed()
 * @mixin \Eloquent
 * @property int $creator_id
 * @property int $editor_id
 * @property-read \App\User $editor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereEditorId($value)
 */
class Post extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    use SoftDeletes;
    protected $fillable = [
        'creator_id',
        'editor_id',
        'word_id',
        'video_id',
        'description_id',
    ];

    protected $dates = ['deleted_at'];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function editor()
    {
        return $this->belongsTo('App\User', 'editor_id');
    }

    public function word()
    {
        return $this->belongsTo('App\Word', 'word_id');
    }

    public function video()
    {
        return $this->belongsTo('App\Video', 'video_id');
    }

    public function description()
    {
        return $this->belongsTo('App\Description', 'description_id');
    }

    public function ils()
    {
        return $this->hasMany('App\Il', 'post_id');
    }

    // CREATE SCOPES --------------------------------------------

    /**
     * @return Model|\Illuminate\Database\Eloquent\Relations\HasMany|null|object
     * @deprecated
     */
    public function scopeRank()
    {
        return $this->ils()->first('rank');
    }

    public function scopeIl()
    {
        return $this->ils()->first();
    }

    public function scopeDeactiveReviews()
    {
        return Review::onlyTrashed()->post()->get();
    }

    public function scopeInReview()
    {
        return $this->il()->reviews()->exists();
    }
}
