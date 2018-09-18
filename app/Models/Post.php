<?php

namespace App\Models;

class Post extends \App\Models\Base\Post
{
    // MASS ASSIGNMENT ------------------------------------------
    protected $fillable = [
        'creator_id',
        'word_id',
        'video_id',
        'description_id',
        'language_id',
        'il'
    ];

    // DEFINING RELATIONSHIPS -----------------------------------


    // CREATE SCOPES --------------------------------------------
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
