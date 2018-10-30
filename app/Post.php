<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Post
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Description[] $descriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Il[] $ils
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $likes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[] $videos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $words
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post countLikes()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post currentDescription()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post currentVideo()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post currentWord()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deactiveReviews()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deletedDescriptions()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deletedVideos()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deletedWords()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post il()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post inReview()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post rank()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUserId($value)
 * @mixin \Eloquent
 */
class Post extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    protected $fillable = [
        'user_id',
    ];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function words()
    {
        return $this->belongsToMany('App\Word', 'wordlinks', 'post_id', 'word_id')->withTimestamps();
    }

    public function videos()
    {
        return $this->hasMany('App\Video', 'post_id');
    }

    public function descriptions()
    {
        return $this->hasMany('App\Description', 'post_id');
    }

    public function likes()
    {
        return $this->belongsToMany('App\User', 'likes', 'post_id', 'user_id')->withTimestamps();
    }

    public function ils()
    {
        return $this->hasMany('App\Il', 'post_id');
    }

    // CREATE SCOPES --------------------------------------------

    public function scopeCountLikes()
    {
        return Post::likes()->count();
    }

    public function scopeRank()
    {
        return $this->ils()->first('rank');
    }

    public function scopeIl()
    {
        return $this->ils()->first();
    }

    public function scopeCurrentWord()
    {
        return $this->words()->first();
    }

    public function scopeDeletedWords()
    {
        return Word::onlyTrashed()->posts()->find($this->id)->get();
    }

    public function scopeCurrentVideo()
    {
        return $this->videos()->first();
    }

    public function scopeDeletedVideos()
    {
        return Video::onlyTrashed()->post()->find($this->id)->get();
    }
    
    public function scopeCurrentDescription()
    {
        return $this->descriptions()->first();
    }

    public function scopeDeletedDescriptions()
    {
        return Description::onlyTrashed()->post()->find($this->id)->get();
    }

    public function scopeDeactiveReviews()
    {
        return Review::onlyTrashed()->post()->get();
    }

    public function scopeInReview()
    {
        return $this->ils()->reviews()->first();
    }
}
