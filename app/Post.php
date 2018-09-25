<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // MASS ASSIGNMENT ------------------------------------------
    protected $fillable = [
        'user_id',
        'word_id',
        'language_id',
        'il'
    ];

    // DEFINING RELATIONSHIPS -----------------------------------
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function word()
    {
        return $this->belongsTo('App\Word', 'word_id');
    }

    public function video()
    {
        return $this->hasMany('App\Video', 'post_id');
    }

    public function description()
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

    // CREATE SCOPES --------------------------------------------

    public function IL()
    {
        return $this->ILs()->whereNotNull('delete_at')->first('rank');
    }

    /**
     * @method static noFlagged($query)
     * @deprecated
     * TODO
     */
    public function scopeNoFlagged( $query ) {
        return $query->whereNull( 'flag_reason' );
    }

    /**
     * @method static findByWordID($query, $id)
     * @deprecated
     * TODO
     */
    public function scopeFindByWordID( $query, $id ) {
        return $query->noFlagged()->where( 'word_id', $id );
    }

    /**
     * @return mixed
     * @deprecated
     */
    public function getNumVotesAttribute() {
        return $this->attributes['num_votes'];
    }

    /**
     * @param $count
     * @deprecated
     */
    public function setNumVotesAttribute($count) {
        $this->attributes['num_votes'] = $count;
    }
}
