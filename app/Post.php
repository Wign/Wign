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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\IL[] $ILs
 * @property-read \App\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Description[] $descriptions
 * @property mixed $num_votes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $likes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviews
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[] $videos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $words
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post activeReviews()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post countVotes($signID)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post currentDescription()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post currentVideo()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post currentWord()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deletedDescriptions()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deletedVideos()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deletedWords()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post findByWordID($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post noFlagged()
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

    public function ILs()
    {
        return $this->hasMany('App\IL', 'post_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review', 'post_id');
    }

    // CREATE SCOPES --------------------------------------------
    //TODO: Count num likes
    //TODO: bool pending review

    public function ScopeCountLikes()
    {
        return $this->likes()->count();
    }

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

    /**
     * Count the number og votes assigned to $signID
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $signID the id of the sign
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @deprecated
     */

    public function scopeCountVotes( $query, $signID ) {
        return $query->where( 'sign_id', $signID )->count();
    }
}
