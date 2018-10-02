<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Post
 *
 * @property int $id
 * @property int $user_id
 * @property int $word_id
 * @property int $language_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Description[] $descriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $likes
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[] $videos
 * @property-read \App\Word $word
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deletedVideos()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereWordId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\IL[] $ILs
 * @property-read \App\User $creator
 * @property-read \App\Language $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviews
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $words
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deletedDescriptions()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post description()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post video()
 * @property mixed $num_votes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post activeReviews()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post findByWordID($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post noFlagged()
 */
class Post extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    protected $fillable = [
        'user_id',
        'language_id'
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

    public function ILs()
    {
        return $this->hasMany('App\IL', 'post_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Language', 'language_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review', 'post_id');
    }

    // CREATE SCOPES --------------------------------------------
    //TODO: Count num likes
    //TODO: bool pending review

    public function ScopeCurrentIL()
    {
        return $this->ILs()->first('rank');
    }

    public function scopeCurrentWord()
    {
        return $this->words()->first();
    }

    public function scopeDeletedWords()
    {
        return Word::onlyTrashed()->word()->find($this->id)->get();
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

    public function scopeActiveReviews()
    {
        return $this->reviews()->get();
    }

    /**
     * Imported from old model "Sign" (combination of video and description)
     */
    /**
     * @method static noFlagged($query)
     * @deprecated
     */
    public function scopeNoFlagged( $query ) {
        return $query->whereNull( 'flag_reason' );
    }

    /**
     * @method static findByWordID($query, $id)
     * @deprecated
     */
    public function scopeFindByWordID( $query, $id ) {
        return $query->noFlagged()->where( 'word_id', $id );
    }

    public function getNumVotesAttribute() {
        return $this->attributes['num_votes'];
    }

    public function setNumVotesAttribute($count) {
        $this->attributes['num_votes'] = $count;
    }
}